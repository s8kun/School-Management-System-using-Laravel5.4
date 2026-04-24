<?php

namespace Tests\Feature;

use App\Models\Classgroup;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SchoolDatabaseDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_relationship_foreign_keys_are_defined()
    {
        $expectedForeignKeys = [
            'students' => [
                'classroom_id' => ['classrooms', 'id'],
                'level_id' => ['levels', 'id'],
            ],
            'teachers' => [
                'classroom_id' => ['classrooms', 'id'],
                'level_id' => ['levels', 'id'],
            ],
            'classrooms' => [
                'classgroup_id' => ['classgroups', 'id'],
            ],
            'subjects' => [
                'level_id' => ['levels', 'id'],
                'teacher_id' => ['teachers', 'id'],
            ],
            'student_subject' => [
                'student_id' => ['students', 'id'],
                'subject_id' => ['subjects', 'id'],
            ],
        ];

        foreach ($expectedForeignKeys as $table => $columns) {
            $foreignKeys = $this->foreignKeysFor($table);

            foreach ($columns as $column => [$referencedTable, $referencedColumn]) {
                $this->assertArrayHasKey($column, $foreignKeys, "Missing foreign key: {$table}.{$column}");
                $this->assertSame($referencedTable, $foreignKeys[$column]['referenced_table']);
                $this->assertSame($referencedColumn, $foreignKeys[$column]['referenced_column']);
            }
        }
    }

    public function test_classroom_and_teacher_relationships_match_the_database_design()
    {
        [$level, $classroom] = $this->createLevelAndClassroom();

        $teacher = Teacher::create([
            'name' => 'Aisha Ali',
            'gender' => 'Female',
            'classroom_id' => $classroom->id,
            'level_id' => $level->id,
            'experience' => '8 years',
            'phone' => '091111111',
        ]);

        Teacher::create([
            'name' => 'Omar Salem',
            'gender' => 'Male',
            'classroom_id' => $classroom->id,
            'level_id' => $level->id,
            'experience' => '5 years',
            'phone' => '092222222',
        ]);

        Subject::create([
            'name' => 'Mathematics',
            'score' => 100,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ]);

        Subject::create([
            'name' => 'Physics',
            'score' => 100,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ]);

        $this->assertCount(2, $classroom->fresh()->teachers);
        $this->assertCount(2, $teacher->fresh()->subjects);
    }

    public function test_subject_score_is_required_and_saved()
    {
        [$level, $classroom] = $this->createLevelAndClassroom();
        $teacher = $this->createTeacher($level, $classroom);

        $this->from('/subjects/create')->post('/subjects', [
            'name' => 'Chemistry',
            'score' => '',
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ])->assertRedirect('/subjects/create')
            ->assertSessionHasErrors('score');

        $this->post('/subjects', [
            'name' => 'Chemistry',
            'score' => 97.5,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ])->assertRedirect('/subjects');

        $this->assertDatabaseHas('subjects', [
            'name' => 'Chemistry',
            'score' => 97.5,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function test_subject_edit_form_updates_score_and_relationship_ids()
    {
        [$level, $classroom] = $this->createLevelAndClassroom();
        $teacher = $this->createTeacher($level, $classroom);

        $subject = Subject::create([
            'name' => 'History',
            'score' => 80,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ]);

        $this->get("/subjects/edit/{$subject->id}")
            ->assertOk()
            ->assertSee('Edit subject')
            ->assertSee('Final Degree');

        $this->post("/subjects/{$subject->id}", [
            'name' => 'World History',
            'score' => 90,
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ])->assertRedirect('/subjects');

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'World History',
            'score' => 90,
        ]);
    }

    public function test_student_and_teacher_controllers_validate_relationship_ids()
    {
        [$level, $classroom] = $this->createLevelAndClassroom();

        $this->from('/students/create')->post('/students', [
            'name' => 'Invalid Student',
            'email' => 'student@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'gender' => 'Male',
            'age' => 14,
            'address' => 'Tripoli',
            'classroom_id' => 999,
            'level_id' => $level->id,
        ])->assertRedirect('/students/create')
            ->assertSessionHasErrors('classroom_id');

        $this->from('/teachers/create')->post('/teachers', [
            'name' => 'Invalid Teacher',
            'email' => 'teacher@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'gender' => 'Female',
            'classroom_id' => $classroom->id,
            'level_id' => 999,
            'experience' => '4 years',
            'phone' => '091234567',
        ])->assertRedirect('/teachers/create')
            ->assertSessionHasErrors('level_id');

        $this->assertDatabaseMissing('students', ['name' => 'Invalid Student']);
        $this->assertDatabaseMissing('teachers', ['name' => 'Invalid Teacher']);
    }

    private function foreignKeysFor($table)
    {
        if (DB::getDriverName() === 'mysql') {
            return DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', $table)
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->get()
                ->keyBy('COLUMN_NAME')
                ->map(function ($foreignKey) {
                    return [
                        'referenced_table' => $foreignKey->REFERENCED_TABLE_NAME,
                        'referenced_column' => $foreignKey->REFERENCED_COLUMN_NAME,
                    ];
                })
                ->all();
        }

        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA foreign_key_list('{$table}')"))
                ->keyBy('from')
                ->map(function ($foreignKey) {
                    return [
                        'referenced_table' => $foreignKey->table,
                        'referenced_column' => $foreignKey->to,
                    ];
                })
                ->all();
        }

        $this->fail('Unsupported database driver for foreign key assertions: '.DB::getDriverName());
    }

    private function createLevelAndClassroom()
    {
        $level = Level::create(['name' => 'Level 1']);
        $classgroup = Classgroup::create(['name' => 'Science']);
        $classroom = Classroom::create([
            'name' => 'Science A',
            'classgroup_id' => $classgroup->id,
        ]);

        return [$level, $classroom];
    }

    private function createTeacher(Level $level, Classroom $classroom)
    {
        return Teacher::create([
            'name' => 'Aisha Ali',
            'gender' => 'Female',
            'classroom_id' => $classroom->id,
            'level_id' => $level->id,
            'experience' => '8 years',
            'phone' => '091111111',
        ]);
    }
}
