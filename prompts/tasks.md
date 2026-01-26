# Implementation Plan

- [x] 1. Set up Laravel project and configure environment
  - Install Laravel 12 with PHP 8.4 using Composer
  - Configure database connection (MySQL/PostgreSQL) in .env file
  - Install Laravel Breeze for authentication scaffolding
  - Install Inertia.js with Vue.js 3 for frontend
  - Configure file storage settings for CV uploads
  - _Requirements: 7.1, 7.2, 7.3, 7.5_

- [x] 2. Create database migrations and models
  - _Requirements: 1.2, 2.5, 3.6, 4.1_

- [x] 2.1 Create job_positions table migration
  - Write migration with fields: id, title, description, city, country, is_active, timestamps
  - Add indexes for is_active and created_at columns
  - _Requirements: 1.1, 1.2, 1.3_

- [x] 2.2 Create applications table migration
  - Write migration with fields: id, job_position_id, full_name, email, cv_path, cover_letter, recruiter_note, timestamps
  - Add foreign key constraint to job_positions table with cascade delete
  - Add indexes for job_position_id, email, and created_at columns
  - _Requirements: 2.1, 2.5, 3.1, 3.5_

- [x] 2.3 Create JobPosition model with relationships
  - Implement JobPosition model with fillable fields
  - Add hasMany relationship to Application model
  - Create active() scope for filtering active positions
  - Add boolean cast for is_active field
  - _Requirements: 1.2, 1.3, 2.1_

- [x] 2.4 Create Application model with relationships
  - Implement Application model with fillable fields
  - Add belongsTo relationship to JobPosition model
  - Create getCvUrlAttribute accessor for CV file URLs
  - _Requirements: 2.5, 3.1, 3.4_

- [ ] 3. Implement file storage service
  - _Requirements: 2.4, 3.4, 5.1, 5.2, 5.3, 7.5_

- [ ] 3.1 Create FileStorageService class
  - Implement storeCv() method to handle CV file uploads with sanitized filenames
  - Add file validation for PDF, DOC, DOCX formats and 5MB size limit
  - Store files in private storage directory
  - _Requirements: 2.4, 7.5_

- [ ] 3.2 Implement CV archive generation
  - Create createCvArchive() method to generate ZIP files of CVs
  - Support filtering by job position or all applications
  - Generate unique filenames for each CV in archive (applicant_name_position.ext)
  - _Requirements: 5.1, 5.2, 5.3, 5.5_

- [ ] 4. Create application service layer
  - _Requirements: 2.5, 3.1, 3.5, 4.1, 4.3, 4.5, 5.1_

- [ ] 4.1 Create ApplicationService class
  - Implement createApplication() method to handle application submission with CV upload
  - Inject FileStorageService dependency
  - _Requirements: 2.5, 2.6_

- [ ] 4.2 Implement applicant management methods
  - Create getAllApplicants() method to retrieve unique applicants with application counts
  - Create getApplicantApplications() method to get all applications for a specific email
  - _Requirements: 4.1, 4.2, 4.3, 4.4_

- [ ] 4.3 Add recruiter note and CV download methods
  - Implement updateRecruiterNote() method for adding/editing notes
  - Implement downloadAllCvs() method using FileStorageService
  - _Requirements: 3.5, 5.1, 5.5_

- [ ] 5. Create form request validators
  - _Requirements: 1.1, 2.2, 2.3, 2.4_

- [ ] 5.1 Create StoreJobPositionRequest validator
  - Add validation rules for title (required, max 255), description (required), city (required, max 100), country (required, max 100)
  - Add custom error messages
  - _Requirements: 1.1_

- [ ] 5.2 Create UpdateJobPositionRequest validator
  - Add same validation rules as StoreJobPositionRequest
  - Add custom error messages
  - _Requirements: 1.4_

- [ ] 5.3 Create StoreApplicationRequest validator
  - Add validation rules for full_name (required, min 2, max 255), email (required, email), cv (required, file, mimes:pdf,doc,docx, max:5120), cover_letter (nullable, max 5000)
  - Add custom error messages for each field
  - _Requirements: 2.2, 2.3, 2.4_

- [ ] 6. Build public job portal controllers
  - _Requirements: 2.1, 2.2, 2.5, 2.6, 2.7_

- [ ] 6.1 Create Public\JobPositionController
  - Implement index() method to display all active job positions
  - Implement show() method to display single job position details
  - Return Inertia responses with job position data
  - _Requirements: 2.1_

- [ ] 6.2 Create Public\ApplicationController
  - Implement store() method to handle application submission
  - Inject ApplicationService to process application
  - Use StoreApplicationRequest for validation
  - Redirect to confirmation page on success
  - _Requirements: 2.2, 2.3, 2.4, 2.5, 2.6_

- [ ] 6.3 Add confirmation page method
  - Implement confirmation() method to display success message
  - Return Inertia response with confirmation view
  - _Requirements: 2.7_

- [ ] 7. Build admin dashboard controllers
  - _Requirements: 1.3, 1.4, 1.5, 3.1, 3.2, 3.3, 3.4, 3.5, 4.1, 4.3, 5.1, 5.5_

- [ ] 7.1 Create Admin\DashboardController
  - Implement index() method to display dashboard overview
  - Show statistics: total positions, total applications, recent applications
  - Return Inertia response with dashboard data
  - _Requirements: 1.3, 3.1_

- [ ] 7.2 Create Admin\JobPositionController resource controller
  - Implement index() method to list all job positions
  - Implement create() method to show job position form
  - Implement store() method to create new job position using StoreJobPositionRequest
  - Implement edit() method to show edit form
  - Implement update() method to update job position using UpdateJobPositionRequest
  - Implement destroy() method to delete job position
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [ ] 7.3 Create Admin\ApplicationController
  - Implement index() method to list all applications with filtering and search
  - Implement show() method to display application details with CV download link
  - Implement updateNote() method to save recruiter notes
  - Implement downloadAllCvs() method to generate and download ZIP of all CVs
  - Implement downloadJobCvs() method to download CVs for specific job position
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 5.1, 5.5, 6.1, 6.2_

- [ ] 7.4 Create Admin\ApplicantController
  - Implement index() method to list all unique applicants with application counts
  - Implement show() method to display all applications for a specific applicant email
  - Show all CV download links for the applicant
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

- [ ] 8. Define application routes
  - _Requirements: 2.1, 2.2, 2.7, 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ] 8.1 Create public routes in web.php
  - Define routes for job listings (GET /jobs)
  - Define route for job details (GET /jobs/{jobPosition})
  - Define route for application submission (POST /jobs/{jobPosition}/apply)
  - Define route for confirmation page (GET /jobs/application/confirmation)
  - _Requirements: 2.1, 2.2, 2.7, 8.4_

- [ ] 8.2 Create admin routes with authentication middleware
  - Define admin dashboard route (GET /admin/dashboard)
  - Define job positions resource routes (GET/POST/PUT/DELETE /admin/job-positions)
  - Define application routes (GET /admin/applications, GET /admin/applications/{id})
  - Define recruiter note update route (PATCH /admin/applications/{id}/note)
  - Define CV download routes (GET /admin/applications/download-cvs, GET /admin/applications/job-position/{id}/download-cvs)
  - Define applicant routes (GET /admin/applicants, GET /admin/applicants/{email})
  - Apply 'auth' middleware to all admin routes
  - _Requirements: 8.1, 8.2, 8.3, 8.5_

- [ ] 9. Create Vue.js components for public portal
  - _Requirements: 2.1, 2.2, 2.7_

- [ ] 9.1 Create Jobs/Index.vue component
  - Display list of active job positions with title, location, and description preview
  - Add link to job details page for each position
  - Style with responsive layout
  - _Requirements: 2.1_

- [ ] 9.2 Create Jobs/Show.vue component
  - Display full job position details (title, description, city, country)
  - Include application form with fields for full name, email, CV upload, and cover letter
  - Add file upload component with validation feedback
  - Handle form submission and display validation errors
  - _Requirements: 2.1, 2.2, 2.3, 2.4_

- [ ] 9.3 Create ApplicationConfirmation.vue component
  - Display success message after application submission
  - Add link to return to job listings
  - _Requirements: 2.7_

- [ ] 10. Create Vue.js components for admin dashboard
  - _Requirements: 1.3, 1.4, 3.1, 3.2, 3.3, 4.1, 4.3_

- [ ] 10.1 Create Admin/Dashboard.vue component
  - Display dashboard statistics (total positions, applications, recent activity)
  - Add navigation links to job positions, applications, and applicants sections
  - _Requirements: 1.3, 3.1_

- [ ] 10.2 Create Admin/JobPositions/Index.vue component
  - Display table of all job positions with title, location, status, and action buttons
  - Add "Create New Position" button
  - Add edit and delete buttons for each position
  - _Requirements: 1.3_

- [ ] 10.3 Create Admin/JobPositions/Create.vue component
  - Create form with fields for title, description, city, country
  - Handle form submission and validation errors
  - Redirect to job positions list on success
  - _Requirements: 1.1_

- [ ] 10.4 Create Admin/JobPositions/Edit.vue component
  - Pre-populate form with existing job position data
  - Handle form submission and validation errors
  - Redirect to job positions list on success
  - _Requirements: 1.4_

- [ ] 10.5 Create Admin/Applications/Index.vue component
  - Display table of applications with applicant name, email, job position, date
  - Add search field for filtering by name or email
  - Add filter dropdowns for job position and date range
  - Add "Download All CVs" button
  - Add link to view application details
  - _Requirements: 3.1, 3.2, 5.1, 6.1, 6.2, 6.3, 6.4_

- [ ] 10.6 Create Admin/Applications/Show.vue component
  - Display full application details (name, email, job position, cover letter, date)
  - Add CV download button
  - Add textarea for recruiter notes with save button
  - Display existing recruiter note if present
  - _Requirements: 3.3, 3.4, 3.5_

- [ ] 10.7 Create Admin/Applicants/Index.vue component
  - Display table of unique applicants with name, email, and application count
  - Add link to view applicant details
  - _Requirements: 4.1, 4.2_

- [ ] 10.8 Create Admin/Applicants/Show.vue component
  - Display applicant information (name, email)
  - Show list of all applications submitted by applicant with job position titles
  - Add CV download links for each application
  - _Requirements: 4.3, 4.4, 4.5_

- [ ] 11. Implement authentication system
  - _Requirements: 8.1, 8.2, 8.3_

- [ ] 11.1 Configure Laravel Breeze with Inertia
  - Install and configure Laravel Breeze with Inertia.js stack
  - Run migrations to create users table
  - _Requirements: 8.1_

- [ ] 11.2 Create seeder for initial recruiter user
  - Create database seeder to add default recruiter account
  - Hash password securely
  - _Requirements: 8.1_

- [ ] 11.3 Protect admin routes with auth middleware
  - Verify all admin routes require authentication
  - Test redirect to login for unauthenticated access
  - _Requirements: 8.2, 8.3_

- [ ] 12. Configure file storage and security
  - _Requirements: 7.5, 3.4_

- [ ] 12.1 Set up private storage disk
  - Configure private disk in config/filesystems.php
  - Create storage directory structure
  - _Requirements: 7.5_

- [ ] 12.2 Create CV download controller method with authentication
  - Implement authenticated route to serve CV files
  - Verify user is authenticated before serving file
  - Use Storage::download() to serve private files
  - _Requirements: 3.4, 7.5_

- [ ] 13. Add search and filtering functionality
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ] 13.1 Implement application search in ApplicationController
  - Add query parameter handling for search term
  - Filter applications by full_name or email using LIKE queries
  - Return filtered results within 1 second
  - _Requirements: 6.1, 6.2_

- [ ] 13.2 Implement application filters
  - Add query parameter handling for job_position_id filter
  - Add query parameter handling for date range filter (start_date, end_date)
  - Support combining multiple filters simultaneously
  - _Requirements: 6.3, 6.4, 6.5_

- [ ] 13.3 Update Applications/Index.vue with search and filters
  - Add search input with debounce for performance
  - Add job position dropdown filter
  - Add date range picker for filtering by submission date
  - Update URL query parameters when filters change
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ] 14. Implement performance optimizations
  - _Requirements: 5.4, 6.2_

- [ ] 14.1 Add database indexes
  - Verify indexes exist on job_positions.is_active, applications.job_position_id, applications.email
  - Add composite indexes if needed for common queries
  - _Requirements: 6.2_

- [ ] 14.2 Implement eager loading for relationships
  - Use with('jobPosition') when loading applications
  - Use with('applications') when loading job positions in admin views
  - _Requirements: 6.2_

- [ ] 14.3 Add pagination to list views
  - Paginate job positions list (15 per page)
  - Paginate applications list (20 per page)
  - Paginate applicants list (20 per page)
  - _Requirements: 6.2_

- [ ] 14.4 Queue CV archive generation for large downloads
  - Create job class for ZIP generation
  - Queue job when downloading more than 50 CVs
  - Notify user when archive is ready
  - _Requirements: 5.4_

- [ ] 15. Add basic styling and UI polish
  - _Requirements: 7.3_

- [ ] 15.1 Style public job portal pages
  - Apply consistent styling to job listings and application form
  - Ensure responsive design for mobile devices
  - Add loading states for form submission
  - _Requirements: 2.1, 2.2_

- [ ] 15.2 Style admin dashboard pages
  - Apply consistent styling to all admin pages
  - Create reusable table component for data lists
  - Add loading states and success/error notifications
  - Ensure responsive design for admin interface
  - _Requirements: 1.3, 3.1, 4.1_

- [ ] 16. Create database seeders for testing
  - _Requirements: 1.2, 2.5_

- [ ] 16.1 Create JobPositionSeeder
  - Generate 10-15 sample job positions with realistic data
  - Mix of active and inactive positions
  - _Requirements: 1.2_

- [ ] 16.2 Create ApplicationSeeder
  - Generate 30-50 sample applications across different job positions
  - Include various applicants with multiple applications
  - Generate sample CV files for testing
  - _Requirements: 2.5_

- [ ] 17. Final integration and testing
  - _Requirements: All_

- [ ] 17.1 Test public job portal flow
  - Verify job listings display correctly
  - Test application submission with CV upload
  - Verify confirmation page displays
  - Test validation errors display properly
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.7_

- [ ] 17.2 Test admin dashboard functionality
  - Test job position CRUD operations
  - Test application viewing and filtering
  - Test recruiter note functionality
  - Test CV downloads (single and bulk)
  - Test applicant listing and detail views
  - _Requirements: 1.1, 1.3, 1.4, 1.5, 3.1, 3.3, 3.4, 3.5, 4.1, 4.3, 5.1, 5.5_

- [ ] 17.3 Test authentication and authorization
  - Verify admin routes require authentication
  - Test login and logout functionality
  - Verify public routes are accessible without authentication
  - _Requirements: 8.1, 8.2, 8.3, 8.4_

- [ ] 17.4 Test file upload security
  - Verify only allowed file types can be uploaded
  - Test file size limit enforcement
  - Verify CV files are stored in private storage
  - Test authenticated CV download access
  - _Requirements: 2.4, 7.5_
