# Plan One: Database Design Analysis and Enhancement Phases

## Scope

This plan is based on the current Laravel app inside `Shcool management/`, its migrations, Eloquent models, seeders, views, controllers, and the existing SQLite database files.

No new business features are added here. The goal is to protect the design already present in the project and fix database design errors that can break existing school data.

## Current Database Shape

### Tables

- `users`
- `admins`
- `students`
- `teachers`
- `levels`
- `classgroups`
- `classrooms`
- `subjects`
- `student_subject`
- `password_resets`
- `migrations`

### Existing Relationship Intent

- One `classgroup` has many `classrooms`.
- One `classroom` has many `students`.
- One `classroom` can have many `teachers`.
- One `level` has many `students`.
- One `level` has many `subjects`.
- One `student` belongs to one `classroom`.
- One `student` belongs to one `level`.
- Students and subjects are many-to-many through `student_subject`.
- One `subject` belongs to one `level`.
- One `subject` belongs to one `teacher`.
- One `teacher` can teach many `subjects`.
- One `admin`, `student`, or `teacher` has one related `user` through the polymorphic `users.userable_id` and `users.userable_type` columns.

## Observed Problems

### 1. Foreign keys are missing

The migrations store relationship columns such as `students.classroom_id`, `students.level_id`, `subjects.teacher_id`, and `student_subject.subject_id`, but they do not define database foreign key constraints.

Current data has no orphan rows, but the database does not prevent future bad rows.

### 2. Teacher relationship columns use the wrong type

`teachers.classroom_id` and `teachers.level_id` are defined as strings:

```php
$table->string('classroom_id');
$table->string('level_id');
```

They should be integer foreign key columns because they point to `classrooms.id` and `levels.id`.

### 3. Subject score is required in the database but not saved by the form

The `subjects` table defines:

```php
$table->float('score');
```

But `SubjectController` creates and updates only:

```php
request(['name', 'level_id', 'teacher_id'])
```

The subject create view also does not submit `score`. This means creating a subject can fail because `score` is required but no value is provided.

### 4. Subject-to-teacher design must stay one-to-many

The project already represents this rule with `subjects.teacher_id` and `Subject::teacher()`.

Because a subject has one teacher and a teacher can teach many subjects, do not add a `subject_teacher` pivot table. The correct enhancement is to keep `teacher_id` on `subjects` and add the missing database protection around it.

### 5. Some model relationships do not match the confirmed design

Examples:

- `Classroom::teacher()` uses `hasOne`, but a classroom can have many teachers.
- `Teacher::subject()` uses `hasOne`, but a teacher can teach many subjects.

These model relationships should be changed to `teachers()` and `subjects()` with `hasMany` relationships so the code matches the database design.

### 6. Deletes can leave broken data

Because there are no foreign keys, deleting a `teacher`, `student`, `subject`, `level`, `classroom`, or `classgroup` can leave rows pointing to records that no longer exist.

### 7. Indexes are incomplete

The current database only has useful indexes on:

- `users.email`
- `users.userable_id + users.userable_type`
- `password_resets.email`
- `password_resets.token`
- `student_subject.student_id + student_subject.subject_id`

The main relationship columns are not indexed, so relationship queries can become slow as data grows.

## Phase 1: Freeze and Verify the Existing Data

Goal: make sure the current database is clean before changing constraints.

Actions:

- Run checks for orphaned rows in:
  - `students.classroom_id`
  - `students.level_id`
  - `teachers.classroom_id`
  - `teachers.level_id`
  - `subjects.level_id`
  - `subjects.teacher_id`
  - `classrooms.classgroup_id`
  - `student_subject.student_id`
  - `student_subject.subject_id`
- Do not continue to constraint migrations until these checks return clean results.

Current SQLite check result:

- No orphan rows found in the current SQLite database.

## Phase 2: Fix Column Types

Goal: make relationship columns match the IDs they reference.

Create a new migration file:

```bash
cd "Shcool management"
php artisan make:migration fix_teacher_relationship_column_types --table=teachers
```

Actions:

- Change `teachers.classroom_id` from string to integer.
- Change `teachers.level_id` from string to integer.
- Update `TeachersTableSeeder` to stop casting these IDs to strings.
- Do this inside the new migration only.
- Do not edit `2017_03_15_151116_create_teachers_table.php`.

Reason:

`classrooms.id` and `levels.id` are integer primary keys. Storing those IDs as strings makes the schema inconsistent and can cause foreign key/index issues later.

## Phase 3: Add Foreign Keys and Indexes

Goal: let the database protect the relationships already used by the app.

Create a new migration file:

```bash
cd "Shcool management"
php artisan make:migration add_foreign_keys_and_indexes_to_school_tables
```

Actions:

- Add a foreign key from `students.classroom_id` to `classrooms.id`.
- Add a foreign key from `students.level_id` to `levels.id`.
- Add a foreign key from `teachers.classroom_id` to `classrooms.id`.
- Add a foreign key from `teachers.level_id` to `levels.id`.
- Add a foreign key from `classrooms.classgroup_id` to `classgroups.id`.
- Add a foreign key from `subjects.level_id` to `levels.id`.
- Add a foreign key from `subjects.teacher_id` to `teachers.id`.
- Add a foreign key from `student_subject.student_id` to `students.id`.
- Add a foreign key from `student_subject.subject_id` to `subjects.id`.
- Add indexes on all foreign key columns.
- Do this inside the new migration only.
- Do not edit the old `2017_*` migration files.

Delete behavior is not confirmed yet:

- Do not add cascade delete rules until the delete behavior is confirmed.
- Do not add automatic child-row deletion unless the project requires it.

## Phase 4: Fix Relationship Methods

Goal: make Eloquent relationships match the confirmed database rules.

No migration is needed for this phase.

Actions:

- Change `Classroom::teacher()` to `Classroom::teachers()` and use `hasMany(Teacher::class)`.
- Change `Teacher::subject()` to `Teacher::subjects()` and use `hasMany(Subject::class)`.
- Keep `subjects.teacher_id` as the source of the subject-teacher relationship.
- Do not add a teacher-subject pivot table.

Important note:

The confirmed rule is: one subject has one teacher, and one teacher can teach many subjects. That is represented by `subjects.teacher_id`.

Do not add a unique index on `subjects.teacher_id`.

Do not add a unique index on `teachers.classroom_id`, because one classroom can have many teachers.

## Phase 5: Fix Subject Creation and Update

Goal: remove the current mismatch between the `subjects` table and `SubjectController`.

No migration is needed for this phase because `subjects.score` already exists in the current migration and database.

Confirmed meaning:

- `score` is the final degree for the subject.

Actions:

- Keep `subjects.score` required.
- Add `score` to the subject create form.
- Add `score` to the subject edit form.
- Add `score` to `SubjectController` create and update request data.
- Add validation for `score`.

## Phase 6: Add Validation Before Database Writes

Goal: prevent bad data before it reaches the database.

No migration is needed for this phase.

Actions:

- Re-enable or add validation in `StudentController`.
- Re-enable or add validation in `TeacherController`.
- Add validation in `SubjectController`.
- Validate that IDs exist:
  - `classroom_id` exists in `classrooms`
  - `level_id` exists in `levels`
  - `teacher_id` exists in `teachers`
- Validate `score` according to the Phase 5 decision.

Reason:

Database constraints protect the data after submit. Controller validation gives users a cleaner error before a database exception happens.

## Phase 7: Improve Seeders Without Changing the Design

Goal: keep seed data consistent with the enhanced schema.

No migration is needed for this phase.

Actions:

- Seed parent tables before child tables.
- Keep the current order:
  - admins
  - levels
  - classgroups
  - classrooms
  - teachers
  - subjects
  - students
  - student_subject
- Replace hard-coded subject `teacher_id` values with lookups by seeded teacher names if needed.
- Keep `student_subject` assignments based on student level and subject level.

Reason:

Hard-coded IDs work only when the seed database is empty and insert order never changes.

## Phase 8: Testing

Goal: prove the database fixes match the confirmed rules before considering the work done.

Create a new test file:

```bash
cd "Shcool management"
php artisan make:test DatabaseDesignTest
```

Tests to add:

- Test that a teacher can have more than one subject.
- Test that a classroom can have more than one teacher.
- Test that each subject belongs to one teacher through `subjects.teacher_id`.
- Test that subject creation includes `score` because `score` is the final degree.
- Test that `teachers.classroom_id` and `teachers.level_id` behave as integer relationship IDs.
- Test that foreign keys reject missing related records after the new foreign key migration is added.
- Test that no unique rule blocks repeated `subjects.teacher_id`.
- Test that no unique rule blocks repeated `teachers.classroom_id`.

Run the tests:

```bash
cd "Shcool management"
php artisan test --testsuite=Feature --stop-on-failure
```

## Phase 9: Migration Strategy

Goal: apply the changes safely using new migration files only.

Actions:

- Add new migration files with `php artisan make:migration`.
- Do not edit the existing `2017_*` migration files.
- Put schema fixes inside the new migration files only.
- For SQLite, remember that altering existing columns is limited. It may require table recreation or a fresh migration reset.
- For MySQL, add constraints only after the orphan checks pass.

Migration files to create:

```bash
cd "Shcool management"
php artisan make:migration fix_teacher_relationship_column_types --table=teachers
php artisan make:migration add_foreign_keys_and_indexes_to_school_tables
```

## Implementation Order

1. Run the orphan checks.
2. Run `php artisan make:migration fix_teacher_relationship_column_types --table=teachers`.
3. Edit only that new migration to fix `teachers.classroom_id` and `teachers.level_id` types.
4. Run `php artisan make:migration add_foreign_keys_and_indexes_to_school_tables`.
5. Edit only that new migration to add foreign keys and indexes.
6. Fix the `subjects.score` create/update mismatch in the form and controller.
7. Fix Eloquent relationship methods for classroom-teachers and teacher-subjects.
8. Do not add unique indexes on `subjects.teacher_id` or `teachers.classroom_id`.
9. Update seeders to match the schema.
10. Add validation in controllers.
11. Run `php artisan make:test DatabaseDesignTest`.
12. Add tests for the confirmed database rules.
13. Run the new migrations.
14. Run `php artisan test --testsuite=Feature --stop-on-failure`.

## First Fix Candidates

These are the safest first changes because they repair clear errors without adding new features:

- Convert teacher relationship IDs from strings to integers.
- Add foreign keys for existing relationship columns.
- Keep `subjects.teacher_id` as the subject-to-teacher relationship.
- Fix the missing `score` handling for subjects.
- Add validation for existing fields only.
