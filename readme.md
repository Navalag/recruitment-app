## Project description

The idea of the project is to allow IT recruiters manage vacancies, and applicants, and send them test tasks with an option to track implementation time.

Once an applicant is created, an email is sent to him, with 2 buttons - to start the test task and to end it.
Both of them are saving time so recruiter can see how many time was spent on task implementation.

Also it has gmail integration (to read email history, see new emails from each applicant and actually send emails).

#### Used technologies:
- Laravel
- Vue.js
- Gmail API
 
## Installation

Clone the repository
```
git clone https://github.com/Navalag/recruitment-app.git
```

Install dependencies
```
composer install
npm install
```

Copy .env.example to .env. Update database and Gmail API credentials.
```
cp .env.example .env
php artisan key:generate
```

Run migration and seeder
```
php artisan migrate --seed
```

Run server
```
php artisan serve
```
