We are going to build a dashboard for recruiters. Its purpose is to add job position and check the applicants to these job positions.

Each job position will have a title, description, location (city, country)
Each application will have a full name, email, CV file, motivation text from the applicant (i am not sure what should be the name of the field), note (from recruiter and seen only by recruiter)
Required functionalities of the dashboard:
Track and manage applications.
manage job positions.
retrieve all CVs and all applicants without the need to select a job position.
retrieve all cvs of a specific applicant (because he can apply to different positions with different CVs)

let's start with the database schema. 
first help me install the needed database and let's build the database schema and tables.

so far I only installed laravel and php using the following command:
```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
```

then I ran these commands to create and init the project:
```bash
laravel new prexta
cd example-app
npm install && npm run build
```
I am following the laravel documentation from: https://laravel.com/docs/12.x