#Child Vaccination Management System

A web-based application designed to help parents manage their children's vaccination schedules. This system allows users to register, add child details, view available vaccines based on age, and book appointments at local health centers.

📋 Table of Contents

About The Project

✨ Features

🛠️ Built With

🚀 Getting Started

Prerequisites

Installation Guide

💡 Usage

🤝 Contributing

License

<a name="about-the-project"></a> ℹ️ About The Project

The Child Vaccination Management System simplifies the complex process of tracking and scheduling childhood immunizations. It provides a central platform for parents to manage their children's vaccination records and for health centers to manage vaccine availability and appointments.

✨ Features

Parent Accounts: Secure registration and login for parents.

Child Management: Parents can add, view, edit, and remove their children's profiles.

Vaccine Catalog: View a list of available vaccines, their descriptions, and the recommended age.

Health Center Listing: Browse participating health centers.

Appointment Scheduling: Book vaccination appointments for a child at a chosen health center.

Admin Panel: A separate interface for administrators to manage vaccines, centers, and schedules.

🛠️ Built With

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

Server Environment: WAMP (Windows, Apache, MySQL, PHP) or XAMPP

🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing.

Prerequisites

You will need the following software installed on your Windows machine:

A WAMP Server Stack: We recommend XAMPP as it is a common and easy-to-use package.

Download XAMPP

A Code Editor:

Visual Studio Code (Recommended)

Git:

Download Git

Installation Guide

Here is a step-by-step guide to setting up the project.

1. Start Your Server
Install and launch your XAMPP Control Panel. Start the Apache and MySQL services.

⚠️ Port 80 Error?
If Apache fails to start due to a port conflict (like Port 80 is in use), follow these steps:

Click Apache's Config button -> httpd.conf.

Search for Listen 80 and change it to Listen 8080.

Search for ServerName localhost:80 and change it to ServerName localhost:8080.

Save the file and restart Apache. You will now access your projects at http://localhost:8080.

2. Clone the Repository
Clone the project files directly into your server's web directory. For XAMPP, this is the htdocs folder.

# Open a terminal or command prompt
cd C:\xampp\htdocs
git clone [YOUR_REPOSITORY_URL] vaccination


(Replace [YOUR_REPOSITORY_URL] with the actual URL of your repository)

3. Set Up the Database
This is a critical step. The project's SQL file may need to be modified.

In your browser, go to http://localhost/phpmyadmin (or http://localhost:8080/phpmyadmin).

Click New to create a new database.

Enter the database name as vaccination and click Create.

Select the vaccination database you just created.

Go to the Import tab.

Database Collation Fix (Important!)
The included vaccination.sql file was likely exported from MySQL 8.0+ and uses the utf8mb4_0900_ai_ci collation. XAMPP often uses MariaDB, which does not support this.

Before importing:

Open the vaccination.sql file (located in the project folder) in your code editor.

Use the "Find and Replace" function (Ctrl+H).

Find: utf8mb4_0900_ai_ci

Replace All: utf8mb4_unicode_ci

Save the file.

Now, back in phpMyAdmin, click "Choose File", select your edited vaccination.sql file, and click Go at the bottom of the page. Your tables will be imported.

4. Configure the Project
The project's PHP files have hardcoded paths that you must change.

Open the project in your code editor.

Navigate to and open the file: C:\xampp\htdocs\vaccination\config\autoload.php.

You will see hardcoded paths from the original WAMP setup.

Change these lines:

// BEFORE
define("BASE_URL","http://localhost/vaccination/");
define("BASE_PATH","c:wamp64/www/vaccination/");


To this:

// AFTER
// Use localhost:8080 if you changed your Apache port
define("BASE_URL","http://localhost:8080/vaccination/");

// This dynamic path finds the project root folder automatically
define("BASE_PATH", dirname(__DIR__) . "/");


Next, open C:\xampp\htdocs\vaccination\config\database.php.

Ensure the settings match your XAMPP (or WAMP) MySQL details. The defaults are:

define("DB_HOST", "localhost"); // Do not add the port here
define("DB_USER", "root");
define("DB_PASS", ""); // Default password is blank
define("DB_NAME", "vaccination");


5. Run the System
You're all set! Open your web browser and navigate to the URL you set in your autoload.php file:

http://localhost:8080/vaccination

💡 Usage

Once the system is running:

Register: Create a new parent account.

Login: Log in with your new credentials.

Add Child: Navigate to the "Add Child" page and fill in your child's details.

View Child: Go to "View Child" to see your registered children.

Book Vaccine: Browse vaccines and health centers to schedule an appointment.

🤝 Contributing

If you'd like to contribute to this project, please follow these steps:

Fork the repository on GitHub.

Clone your fork locally.

Create a new branch for your feature or bug fix.

Make your changes and test them.

Commit your changes with descriptive commit messages.

Push your changes to your fork on GitHub.

Submit a pull request to the main repository.

License

Distributed under the MIT License.