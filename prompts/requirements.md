# Requirements Document

## Introduction

The Recruiter Dashboard is a web application that enables recruiters to manage job positions and track applicants. The system consists of two main components: a public-facing job application interface accessible from the company website, and a private recruiter dashboard for managing positions and reviewing applications. Recruiters can create and manage job postings, review applications with uploaded CVs and optional cover letters, and maintain private notes about candidates. The application provides comprehensive views of all applicants and their submissions across multiple job positions.

## Glossary

- **Recruiter Dashboard**: The private web interface accessible only to recruiters for managing job positions and reviewing applications
- **Public Job Portal**: The public-facing pages on the company website where applicants can view job positions and submit applications
- **Job Position**: A posted job opening with title, description, and location details
- **Application**: A candidate's submission for a specific job position, including personal information and documents
- **CV**: Curriculum Vitae file uploaded by an applicant
- **Cover Letter**: The motivation text submitted by an applicant explaining their interest in the position
- **Recruiter Note**: Private annotation added by a recruiter to an application, visible only to recruiters
- **Applicant**: A person who has submitted one or more applications to job positions

## Requirements

### Requirement 1

**User Story:** As a recruiter, I want to create and manage job positions, so that I can post available roles and attract candidates

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL provide a form to create a new Job Position with title, description, city, and country fields
2. THE Recruiter Dashboard SHALL store each Job Position with a unique identifier in the database
3. THE Recruiter Dashboard SHALL display a list of all Job Positions with their title, location, and creation date
4. THE Recruiter Dashboard SHALL allow recruiters to edit existing Job Position details
5. THE Recruiter Dashboard SHALL allow recruiters to hide Job Positions that are no longer available
6. THE Recruiter Dashboard SHALL allow recruiters to delete Job Positions that are no longer needed

### Requirement 2

**User Story:** As an applicant, I want to submit my application to a job position from the company website, so that I can be considered for the role

#### Acceptance Criteria

1. THE Public Job Portal SHALL display a list of active Job Positions with their title, description, and location
2. WHEN an applicant selects a Job Position, THE Public Job Portal SHALL display an application form with fields for full name, email, CV file upload, and optional Cover Letter text
3. THE Public Job Portal SHALL validate that the full name field contains at least two characters
4. THE Public Job Portal SHALL validate that the email field contains a valid email address format
5. THE Public Job Portal SHALL accept CV file uploads in PDF, DOC, or DOCX format with a maximum size of 5MB
6. THE Public Job Portal SHALL store the Application with all submitted data linked to the specific Job Position
7. WHEN an Application is successfully submitted, THE Public Job Portal SHALL redirect to a confirmation page displaying a success message

### Requirement 3

**User Story:** As a recruiter, I want to view and manage applications for each job position, so that I can review candidates and make hiring decisions

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL display a list of all Applications for a selected Job Position
2. THE Recruiter Dashboard SHALL show applicant full name, email, submission date, and application status for each Application in the list
3. WHEN a recruiter selects an Application, THE Recruiter Dashboard SHALL display the full application details including Cover Letter text
4. THE Recruiter Dashboard SHALL provide a download link for the CV file attached to each Application
5. THE Recruiter Dashboard SHALL allow recruiters to add or edit a Recruiter Note for any Application
6. THE Recruiter Dashboard SHALL ensure Recruiter Notes are visible only to authenticated recruiters and hidden from applicants

### Requirement 4

**User Story:** As a recruiter, I want to view all applicants across all job positions, so that I can get a comprehensive overview of my talent pool

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL provide a view that lists all Applicants who have submitted Applications regardless of Job Position
2. THE Recruiter Dashboard SHALL display each Applicant's full name, email, and the number of Applications they have submitted
3. WHEN a recruiter selects an Applicant from the list, THE Recruiter Dashboard SHALL display all Applications submitted by that Applicant
4. THE Recruiter Dashboard SHALL show the Job Position title for each Application in the Applicant's history
5. THE Recruiter Dashboard SHALL provide download links for all CV files submitted by the selected Applicant across different Applications

### Requirement 5

**User Story:** As a recruiter, I want to download all CVs at once, so that I can efficiently review candidate qualifications offline

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL provide a function to download all CV files from all Applications as a single archive file
2. WHEN a recruiter requests to download all CVs, THE Recruiter Dashboard SHALL generate a ZIP archive containing all CV files
3. THE Recruiter Dashboard SHALL name each CV file in the archive with the applicant's name and Job Position title to ensure uniqueness
4. THE Recruiter Dashboard SHALL complete the archive generation within 30 seconds for up to 100 CV files
5. WHEN a recruiter views a specific Job Position, THE Recruiter Dashboard SHALL provide a function to download all CVs for that Job Position only

### Requirement 6

**User Story:** As a recruiter, I want to search and filter applications, so that I can quickly find relevant candidates

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL provide a search field that filters Applications by applicant name or email
2. THE Recruiter Dashboard SHALL update the displayed Application list within 1 second of entering search criteria
3. THE Recruiter Dashboard SHALL provide filter options to view Applications by Job Position
4. THE Recruiter Dashboard SHALL provide filter options to view Applications by submission date range
5. THE Recruiter Dashboard SHALL allow combining multiple filters simultaneously

### Requirement 8

**User Story:** As a recruiter, I want to access the dashboard securely, so that applicant data remains confidential

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL require authentication with email and password before granting access
2. THE Recruiter Dashboard SHALL restrict all recruiter features to authenticated users only
3. THE Recruiter Dashboard SHALL redirect unauthenticated users to a login page when attempting to access protected routes
4. THE Public Job Portal SHALL be accessible without authentication for viewing Job Positions and submitting Applications
5. THE Recruiter Dashboard SHALL maintain separate URL routes from the Public Job Portal (e.g., /admin/* for recruiter features)

### Requirement 7

**User Story:** As a system administrator, I want the application to be built with Laravel and modern web technologies, so that it is maintainable and scalable

#### Acceptance Criteria

1. THE Recruiter Dashboard SHALL be implemented using Laravel framework version 12 with PHP 8.4
2. THE Recruiter Dashboard SHALL use a relational database (MySQL or PostgreSQL) for data persistence
3. THE Recruiter Dashboard SHALL implement the frontend using Vue.js or React with Inertia.js for seamless integration
4. THE Recruiter Dashboard SHALL follow Laravel best practices including Eloquent ORM for database operations
5. THE Recruiter Dashboard SHALL store uploaded CV files securely in a dedicated storage directory with access controls
