FOODI FOODI Restaurant Website Development
Introduction
The WebWiz Group embarked on the development of the FOODi FOODI restaurant website with
a primary focus on creating an aesthetically pleasing and user-friendly interface. The project
aimed to integrate best practices in UI design, drawing inspiration from successful restaurant
websites.
The relationships between tables are as follows:
• User to Order: One-to-Many
• User to Reservation: One-to-Many
• Order to Order Item: One-to-Many
• Menu to Order Item: One-to-Many
This design allows for tracking of which user places an order or makes a reservation, what items
are included in each order, and what menu items are available. The ON DELETE CASCADE
constraint ensures that all related data is removed when a user is deleted, maintaining referential
integrity.
Technologies Used
Three APIs
-Map API on About us page, allowing user to locate our restaurant's location.
-Time stamp on order confirmation page, showing the time when did the customer placed the order.
-API for randomly generated cocktail to enhance interaction in our home page.
Jason&Ajax the user's information are sent to the server using AJAX in JSON format
Advanced security measures
User role - User roles help in defining access levels and permissions for different types of users within a
system, ensuring that users have appropriate access to resources.
Hashed password- We put hashed password to secure our customer’s password.
Constraints for password: We put more striction on password setting to increase security of the
Challenges Faced
-Issues with updating and delete information to the database due to database constraints
-Redesign the look of the pages
-Sending and Receiving Json Data and working with AJAX
-Learning to know how to work with foreign key a
UI Design
• Detailed discussion on the chosen visual layout, color scheme, and button placements.
• Integration of UI design best practices.
Conclusion
• Recapitulation of the project's goals and achievements.
• Acknowledgment of successful collaboration and teamwork.
Screenshots of all pages
1. Home:
Reservation

1.Reservation:
2. confirmation
3.Reservation Edit
4.Reservation Cancel
5.About Us
2. Order:
3. Login:
4. Sign up:
5. Cart:
6. Menu:
7. Privacy terms and conditions:
8. Admin CRUD for menu:
9. Admin CRUD Add menu:
10. Admin CRUD Delete menu:
11. Admin CRUD Update menu:
12. Admin CRUD for user:
13. Admin CRUD Update for user:
14. Admin CRUD Add New user:
15. Admin CRUD Delete user:
18.Reset password
19.Order Confirmation
20.Account Information
21.
22.Order history
23.User experience
https://foodifoodi.000webhostapp.com/index.html
