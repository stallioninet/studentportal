# studentportal
Student Portal Plugin

  1. Setup a student in the WordPress site 

      a. create a user in WP users

      b. set levels in Wishlist Member

      c. set courses in Learndash

      d. Manage packages

      
  2. API for Student Setup.

      a. Rest API for the User Creation (External app 
         will call the API to create the user and set 
         up the courses).

      b. API with authentication / key (for security
         purpose)

      c. API Request parameters will be:

          -First Name

          -Last Name

          -Username

          -Email

          -Password

          -Enrolled Courses
          
      d. API Response data will be

           -User created flag

           -User updated flag (if user exists)

           -Success flag
