# FlyStay Project

## Description

This FlyStay platform provides a complete reservation system with this user roles: Admin, Customer,Hotel Agent, Flight Agent, and Finance Officer.

The system manages flights, hotels, and cities, including detailed information for each flight through the FlightCabin entity, which stores seat availability, cabin type, and pricing.

Users can manage their accounts, edit personal information, and view booking details. The platform supports hotel rating functionality and integrates Stripe for secure online payments.

During every stage of the booking process—whether for flights or hotels—the system automatically sends email notifications to users about their payment status, from the initial booking to completion.

Users can also cancel their bookings. If the payment was made via Stripe, refunds are processed automatically. For Cash payments, users must visit the office in person and present the confirmation email sent by the system to receive their refund securely.

## Features

### Role & Permissions

# Admin

The Admin has full control over the FlyStay platform and is responsible for system-wide configuration and management.
Key capabilities include:

-   **Data Management**: View and manage all airports, cities, and countries registered in the system.
-   **User Administration**: Create, update, and remove user accounts. Assign or revoke roles and permissions for individual users or entire roles.
-   **Access Control**: Define new roles, configure permissions, and manage the mapping between roles and permissions to maintain secure and structured access.
-   **Flight Management**: Create, edit, and delete flights, including management of related FlightCabins, which define seating details, pricing, and flight class types.
-   **Hotel Management**: Oversee hotels, rooms, and hotel bookings to ensure accuracy and operational consistency.
-   **Booking Oversight**: Manage both flight and hotel bookings, ensuring data integrity and assisting with exception handling when necessary.
-   **Ratings Supervision**: Monitor and moderate hotel ratings to maintain quality and authenticity of user feedback.
-   **Payment Control**: Review and manage payment transactions, ensuring successful integration with payment gateways such as Stripe.

# Flight Agent

-   **Flight Management**: Create, update, and delete flights, ensuring accurate scheduling, destinations, and pricing across the platform.

-   **FlightCabin Administration**: Manage cabin details for each flight, including seat availability, cabin types, and fare configurations.

-   **Booking Oversight**: Supervise and validate flight bookings to ensure customer reservations are accurate and up to date.

-   **Operational Accuracy**: Maintain consistent and reliable flight data, supporting a seamless booking and travel experience for users.

# Hotel Agent

-   **Hotel Management**: Create, update, and delete hotel records, ensuring all property details, locations, and contact information are accurate.

-   **Room Administration**: Manage hotel rooms, including room types, availability, pricing, and capacity settings to maintain up-to-date listings.

-   **Ratings Management**: Monitor and moderate customer reviews and ratings to ensure authenticity and maintain service quality.

-   **Operational Coordination**: Work closely with the Admin and Finance Officer to ensure smooth hotel operations, accurate data synchronization, and proper handling of payments and refunds.

# Finace Officer

-   **Payment Management**: Access and review all payment transactions across the platform to ensure accuracy, transparency, and proper financial tracking.

-   **Payment Details Review**: View detailed information for each payment, including method, amount, status, and related booking data, supporting effective auditing and refund processing.

# Customer

-   **Account Management**: Register, log in, log out, reset password, and change password. Customers can also sign up or log in using their Google account for quick access.

-   **Profile Control**: View and update personal profile information at any time.

-   **Flight Services**: Browse all available flights, create or update flight bookings, and cancel bookings when needed.

-   **Hotel Services**: Create, update, or cancel hotel bookings, and view detailed information about each hotel before booking.

-   **Booking Overview**: Access and manage all personal bookings in one place for both flights and hotels.

-   **Ratings Management**: Create, update, and delete hotel ratings, helping improve service quality through feedback.

## Additional Features

-   **Rate Limiting**: The application implements Laravel’s built-in rate limiting to control API request frequency. Each user is limited to 60 requests per minute, based on either their authenticated ID or IP address, ensuring fair use and preventing abuse of system resources.

-   **Mail, Notifications, and Queue Jobs**: The platform uses Laravel’s mail and notification systems combined with queued jobs to handle background tasks efficiently. Users automatically receive emails and notifications at each stage of their booking, ensuring timely updates without impacting application performance.

-   **Custom Commands**: Several Artisan commands are implemented to automate time-sensitive booking operations. For hotels, bookings are automatically canceled 24 hours before check-in if payment is not completed. For flights, cancellations occur two hours before departure under the same condition. Another command updates hotel booking statuses from confirmed to complete once the stay period has ended, ensuring consistent data accuracy.

-   **Caching**: The application leverages caching to store frequently accessed data such as flight lists, hotel details, and city information. This reduces database load, speeds up response times, and enhances the overall user experience through optimized data retrieval.

-   **Weather Integration**: The system automatically sends an email to users on the day of their flight, providing real-time weather information for their destination. Additionally, users can manually check current weather data for any city directly through the platform, improving travel planning convenience.

-   **VirusTotal Integration**: The system integrates with the VirusTotal API to scan all uploaded images, ensuring that no malicious or harmful content is stored or processed within the platform.

## Technologies Used:

-   Laravel 12
-   PHP
-   MySQL
-   XAMPP
-   Composer
-   Postman Collection: Contains all API requests for easy testing and interaction with the API.

## Packages Used

1.  `tymon/jwt-auth`: Provides secure token-based authentication for API users, enabling stateless sessions and RESTful API access.
2.  `stripe/stripe-php` : Handles online payment processing, including credit/debit card transactions and refunds, ensuring secure financial operations.
3.  `laravel/socialite` : Allows users to register and log in using popular social platforms like Google, simplifying authentication and improving user experience.
4.  `spatie/laravel-permission` : Enables granular role and permission management, allowing secure assignment of roles and controlled access to features throughout the platform.

## Diagrams

### ERD Diagram

View the ERD Diagam [here](https://dbdiagram.io/d/68138c101ca52373f51bdd4b)

### Use Case Diagram

View the Use Case Diagram [here](https://viewer.diagrams.net/?tags=%7B%7D&lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=Finally%20UseCase%20FlyStay%20Diagram.drawio.png&dark=auto#R%3Cmxfile%20scale%3D%221%22%20border%3D%220%22%3E%3Cdiagram%20name%3D%22Page-1%22%20id%3D%222ZnYLlT7xER1uz0PLg6Z%22%3E7V1bc5s4GP01eQyDLgh4dJ0m2d12mm2m3fapg43i0GKUwTiJ%2B%2BtX2IDRxQ4m3OwmDy0IEHD0cfRd5TM0nj9fxd7D%2FUfm0%2FAMmv7zGbo4gxCYGPL%2F0pZV1mK77qZlFgd%2B1rZtuA1%2B0%2FzSrHUZ%2BHQhnJgwFibBg9g4ZVFEp4nQ5sUxexJPu2OheNcHb0aVhtupF6qt%2FwV%2Bcr9pdaC9bb%2Bmwew%2BvzMg2fvNvfzk7E0W957PnkpN6P0ZGseMJZut%2BfOYhil6OS6b6y53HC0eLKZRUuWC2%2BvltxH7jT5ckI%2F%2F%2FqY3EVjNzrPXePTCZfbC2cMmqxyBmC0jn6admGfo3dN9kNDbB2%2BaHn3ig87b7pN5yPcA31QfKnvORxon9LnUlD3kFWVzmsQrfkp%2BlCADZ1dlUnMOzBzFp%2B0gEBMYwNk035fHgOQne9ngz4qbbPHhGxlEB8DlKHBdhqvbxEsf%2F3a1SOhcgY%2B%2FdSJi5IXBLOLbU44RjXlDik3ARW6UHZgHvp9ergVbHI4G8MYmlsEGKtggx7SMNGoLZ1eD8%2Fq20BzN0leVUeYf10O6uZyHo2nCyqh%2B8CY0vGGLIAlYiu6EJQmba2BPmCTMbJmEQUTHBbU0hLhjCoBDDdxIgzZpC22owj1eLjhIXDo7AboBTImI6bnj9AwqthRQvwb0ibfcxOwu4HjK0PJO%2Bay267svwcWvDscsTMHn1yEw8QCFaTuLklL73fqPty%2BSmP2ipSOmSd6PLpsBHiHLIEgE3yUaAsHQsC11AKzWBkAlaxXyyB%2BlSgLfi1hERZhFpuUIxatv5Z3v2XnrnYvn8qGLVb73HCTriwwr2%2Fsu7G0vS3fKV93QOOBApBPEft5ZsGU8pRU%2B78SLZzSpILLUF3QidehLw2ppvqm8LaahlwSPoialG%2BfsDjcsSKm9UATArm8672Pz6tllZdVH6glZkj5hAyz2tMFG6YkLx3puz097SE9YKKJZQFNfWu38w9tK60cvSpVTaOZzX0N84VvU8bGOFxw4QYQ0xAs2MJAtTnQ6WgDEAKhDWrCRDugSzmNvEkTHBjYnV8cVwAauTonjGjPoEmzcAQdnbDpwFq5KwjkRDISEuXkgUmfRcDAJIyIKqCl11D8Hk5ak1TAREiUWum5nMrvutwdBRsMSZFcSZICseoJsEUvqSdZL%2Bpdk1a9zzZK1b65D%2B7mBic2WBs3u2aqzVaOiUNPWCC8a0xwo4LqDrdMcXGIjryHNATrYcKAkz6ZOT3O1zrbWVIf8m9Ih%2FZmx%2BbEBjSxRGbZ0GEOjSwvZUW2OZua749POcsJ8eVZzhjWryTayLCxVJzUsOdCw9Ci9T2mOare9CesLwppz6FCEFYkyBuT4QWVhlT3o8rP0L62q4Ttacoih%2BemBxl6qSjU3gRFsE8c%2BxAGMAEYWbihel2oQ4tx2bukiSLnPrZu5TbXlRv5c49g5Fs1X5zzrVPV193goP3OJjmYtSPRuyd0l6w1Ajx3bkEy8wutRFmizS2XNVeNJzc9%2FxxDOyL%2Fsl2c%2FPKzZD0qTVt1oBpaoAdlDm%2F0KA7JdZe2UhDVn16EIq%2BQts9y6zjIkMmmhGfQhrF8WV6u%2Fbm4tnHz1x7O76erHP9NzdVprT1PrN1QPkWNgMUp07urCRMAyYEtzm3YIYLNs0azn%2FOBJsj5b6CL1%2B2R2KGShBOpNt%2BbUBuWeZJnrnS5UN8QVTd%2BgSFA7g7xjM3e3X9DECxr0CvfLIBg4irfY1dl6OsukNfp4CzIfTCDDis0hU%2FrsiVVT3SBAmt%2FswRGIaseNY%2BoltEQaGwopGOUdY7%2B4eX0qHGI7BnJ20HxvHNJW6P%2FYDJbKDDKsXEEk5%2F%2BSPF5wKIPYtswgcjVB7wyiBvdbc8R1FITGwOA9bv9Em1Efj24tVqrFvItk4mPgB13saZ%2BQDoQfoKxhoLppbEq5DBfcgfGDWs5R8MOXBY1PJfAEiZq6gjUJQq0FnvSmeSeuz6OwRnTOz30COxSukJ0QSn5f5ZRXQl7oqXeuAKr7s5RmFdLF2hwh3jxlgs2%2FvIXLyDxYLE4oio0sadAx6p1J4BuTHMokYGCeUYVKaqsdlqR2yAGZ%2FplEW1hTSo3VMkl%2BJPNxnAqdYEgU3QRpQy2dMsqbp%2FRwRhm4q9StSSiWLRIKGByfqI5SpSJSQyiXH0Sv6akwirXTgu2PTjpxmp4YnQzMbyql5NbOHye26NIvkuOGwyf73KaNFpS8gihilnhZtul5Q4mkFlZjtrB%2FVeTNoXoocwzLo4rkROPKARZpcacBah57HKg33mpO8%2Fsev1aB5dQdqI21dMkMucn8xgyVmWFY%2FlNZp6hNFFCsKkH5%2FmCIAu5xnraxmEi%2FZREIQAPLa8Xp4i1t1UXoxwB2QRenZYTAgXlJJdMWyBkXlX0aQEo2x0NTLaDqJL0MIi%2Fi45p7MqLJYuPKIGG6cKQfPPLNWbr56e4umKZr8m2O8AcoHVSE%2FpiXRMQaLaStYjf9OKnOp02a72h9beNaICdpOJ3qmN0nE2I1tQaBiWQGLzKu%2B9P3%2Fkgv0l52eJnAh%2BVF2pWvdXi8XNIc5QLxLvnb%2Bvb3Mvg5N38wNrm%2B%2Fvnzc%2Fz5WVMs9OXBP67kXYUuNLK625kEJQXQ1nmS2src1Q7Jaep%2F0pfdXDLvbrHuzV6UJv%2BtTB3KH44JpZ7kLODeCUTV%2F8ap9hf%2BKQSCXXmIdHk2nRJIJ0Hxk6YP1Ct9yAv72XUdTk6eyVsUEdg92o9apPebJdtV25uPfvdMG0qFmGZ9925p4xQrhrqlDatX2pAjH3bd1ahsLGX5EnNoWsee0HdaEaDxNRWFRJV8TcdJKjqHNkG4Z145xcB4t7xi98kryoLZpG71gOVIsmkNjlf2BMqlxN7GKKNyReLrqIG4hiOtAaYtCGhvVVy9oX6a1UWN8IOuNHG31A5F74B1F1aS9Zc%2BF1bSy6o2Oi7k7ypqRxYvb1XpqPyTHK9jEEgMJGY6IS2DtPf7J%2FpRgW8MsotBKrtL%2Bw2Xy%2Bv%2F1a0psqTf7cKD0y80NUW392z9I2qbCCzf8hteYalyKPZ19KCGYq0uU%2B%2F0cJ9mfdCh3FA5NrtHZnsjB0ktUNZbrFxwCMSEfktWVFokB767%2FV3czenbnxdG7%2F8H%3C%2Fdiagram%3E%3C%2Fmxfile%3E)

## Installation

### Prerequisites

Ensure you have the following installed on your machine:

-   **XAMPP**: For running MySQL and Apache servers locally.
-   **Composer**: For PHP dependency management.
-   **PHP**: Required for running Laravel (mark: version 8.2 or less).
-   **MySQL**: Database for the project.
-   **Postman**: Required for testing the requests.

## Steps to Run the Project

1.  ### Clone the Repository
    ```bash
    git clone https://github.com/alialjndy/FlyStay.git
    ```
2.  ### Navigate to the Project Directory
    ```bash
    cd FlyStay
    ```
3.  ### Install Dependencies
    ```bash
    composer install
    ```
4.  ### Create Environment File
    ```bash
    cp .env.example .env
    Update the .env file with your database configuration (MySQL credentials, database name, etc.).
    ```
5.  ### Generate Application Key
    ```bash
    php artisan key:generate
    ```
6.  ### Rum Migrations
    ```bash
    php artisan migrate
    ```
7.  ### Run Country Seeder
    ```bash
    php artisan db:seed --class=CountrySeeder
    ```
8.  ### Import Airports
    ```bash
    php artisan app:import-airports
    ```
9.  ### Run all seeders
    ```bash
    php artisan db:seed
    ```
10. ### Run the Cron Job

    To automate tasks like sending email notifications or running background processes, set up a cron job.

    1.  Add your email address to the database either by using a seeder or manually. This will ensure that you receive notifications.
    2.  Start the Queue Worker
        ```bash
        php artisan queue:work
        ```
    3.  Start the Schedule Worker
        ```bash
        php artisan schedule:work
        ```

---

### Postman Collection:

You can access the Postman collection for this project by following this [link](https://lively-resonance-695697.postman.co/workspace/My-Workspace~f4d36390-4463-41a5-819e-d347e13c96b0/collection/37833857-1b55c15e-6542-4384-9c6a-42b889292af1?action=share&creator=37833857). The collection includes all the necessary API requests for testing the application.
