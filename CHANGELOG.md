# Staffm Change Log

## 2.6.2

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix bug in cost center show with qualification authorization.

## 2.6.1

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix authorizations for qualification status.
- [DEV] Add description to Json-File.

## 2.6.0

Supports Typo3 8.7.0 - 8.7.99

- [FEATURE] Authorizations for admins and qualification status.
- [BUGFIX] Fix reminder date in qualification.

## 2.5.4

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix vhs version.

## 2.5.3

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix vhs version.

## 2.5.2

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix vhs version.

## 2.5.1

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix vhs version.

## 2.5.0

Supports Typo3 8.7.0 - 8.7.99

- [UPDATE] Update vhs version.
- [FEATURE] Add composer functionality.
- [UPDATE] Show description of categories.
- [COMMENT] Resolve TODO.
- [UPDATE] Change authorization for admins about settings.
- [UPDATE] Change qualification choose.
- [BUGFIX] Fix correct links in show templates.
- [BUGFIX] False template in search form of employees.
- [BUGFIX] Fix Error if edit a qualification with no cost center responsible.
- [FEATURE] New Button for qualification status.
- [UPDATE] Add notice information to memory email.

## 2.4.0

Supports Typo3 8.7.0 - 8.7.99

- [FEATURE] Add history function for notes from actually user.
- [BUGFIX] Show right templates if admin is logged in.
- [UPDATE] Place Loading Circle.
- [UPDATE] Style memory date.
- [UPDATE] Change picture size in edit form.
- [BUGFIX] Fix Edit Template employee after assign qualifications.
- [FEATURE] Implement memory function for qualifications of the employees.
- [UPDATE] Fix status designation.

## 2.3.1

Supports Typo3 8.7.0 - 8.7.99

- [UPDATE] Clean Code and add a widget to cost center template.
- [UPDATE] Style note field in qualification choose list.
- [UPDATE] Uniform style for all Tables in lists and show forms.
- [CLEANUP] Remove testing code.
- [FEATURE] Add Classes for Activity in Employee Qualification. Implementation in Forms are later.
- [BUGIFX] Show correct form for supervisor, normal users if qualifications where updatet/deletet.
- [BUGIFX] Show correct form for supervisor, normal users and not logged in users.
- [BUGIFX] Show correct form for supervisor after editing an employee.
- [BUGFIX] Fix Qualification editing on cost supervisor/center responsible list.
- [UPDATE] Show Qualification status just for cost center responsibles.
- [UPDATE] Change textarea and datepicker arrangement in qualification.

## 2.3.0:

Supports Typo3 8.7.0 - 8.7.99

- [DOCS] Update extension version.
- [CODESTYLE] Change button attributes.
- [BUGFIX] Show correct list of positions if a position is select for an employee.
- [UPDATE] Change User edit form action.
- [UPDATE] Representative assignment with more functionality.
- [BUGFIX] Fix error in qualification list of supervisor.
- [FEATURE] Added a email function to send a information to deputies if they where assigned.
- [UPDATE] Qualification status get from settings.
- [UPDATE] Language labels for assigned representations.
- [BUGFIX] Fix in MitarbeiterRepository the query with cost centers #14.
- [FEATURE] Show assigned representations in User edit forms.
- [FEATURE] Set a qualification status in settings that normal users don´t see.
- [FEATURE] Authorization for cost center responsibles and deputies.
- [FEATURE] Possibility to select representatives.
- [UPDATE] Change colors of selected items.
- [UPDATE] Update Excel Export for employee qualifications.
- [CODESTYLE] Reorder entries.
- [FEATURE] Widget and a search form for selecting a responsible for a cost center added.
- [FEATURE] Excel Export in supervisor edit form for employee all qualification selection.

## 2.2.1:

Supports Typo3 8.7.0 - 8.7.99

- [DOCS] Update extension version.
- [DOCS] Update Changelog.
- [BUGFIX] Fixed a bug in edit forms for employee, to select qualifications or categories.
- [UPDATE] Add additional attributes to list views.
- [GIT] Add nbproject folder to gitignore.
- [TASK] Add titles on mouseover for categories.
- [BUGFIX] Fixed a bug in supervisor edit form for employee, to select qualifications.

## 2.2.0:

Supports Typo3 8.7.0 - 8.7.99

- [BUGFIX] Fix Error in MitarbeiterController by delete Cachings of qualifications #12.
- [FEATURE] New Templates for supervisors to edit their employees.

## 2.1.2:

Supports Typo3 8.7.0 - 8.7.99

- [DOCS] Update Changelog.
- [GIT] Update gitignore file.
- [CLEANUP] Remove unused code in qualification.
- [UPDATE] Improve Caching after update an employee.

## 2.1.1:

Supports Typo3 8.7.0 - 8.7.99

- [DOCS] Update Changelog.
- [UPDATE] Update Cache deletes in class Mitarbeiter.
- [BUGFIX] Fixed the Cost Center Responsible Bug #7.
- [BUGFIX] Fixed the Qualification Excel Export #6.

## 2.1.0:

Supports Typo3 8.7.0 - 8.7.99

- [FEATURE] Add a selection menue for employees with category buttons.
- [FEATURE] Add a selection menue for qualifications with category buttons.
- [FEATURE] Add Categories to qualification, for a better usage.
- [BUGFIX] Fixed caching bug, by changing name (Mitarbeiter update action) who was send to cache class.

## 2.0.0:

Supports Typo3 8.7.0 - 8.7.99

- [GIT] Update git files.
- [BUGFIX] Fix the Qualification history bug #4
- [DOCS] Update Changelog.
- [FEATURE] Implement new Action in QualificationService.
- [COMMENT] Add/fix/alter comments in the code.
- [GIT] Add gitignore file.
- [UPDATE] Expand the qualification selection.
- [FEATURE] Outsourcing Caching mechanism.
- [DOCS] Change Docs.
- [CLEANUP] Removed unused files.
- [FEATURE] Add history to a employee qualification who saves the status.
- [GIT] Update issue templates.
- [BUGFIX] Change TCA in Employeequalification, because it occurs a database error in the backend.
- [UPDATE] Add Class History for employee qualifications.
- [CLEANUP] Remove some Files and Testing Code lines.
- [FEATURE] Add extended qualification management with qualification status and memory function.

## 1.0.0:

Supports Typo3 8.7.0 - 8.7.99

- [FEATURE] Extension for employee management.