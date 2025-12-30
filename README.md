# CSE3300 - News Web Site
Nicole Dai & Ayla Burba

Homepage Link: http://ec2-3-22-63-225.us-east-2.compute.amazonaws.com/~Nicole/module3-group-module3-522011-517938/homepage.php
<br>
Creative Portion: We implemented a liking system where a signed in user can like stories (similar to instagram). A user can like and unlike any story including their own (if they wanted). However, they cannot like the same story more than one time. To do this, we created a table specifically for likes. It stores columns including id, story_id, and username in order to keep track of which users liked which story. In our code, we would run a query that would check if a user has already linked to a story idea. If that count was 0, then we could send another query to insert a new entry (meaning that a user liked a story). Conversely, if the count is greater than 0, then we can confirm that there is a story a user can unlike.




<br><br><br><br><br><br><br><br><br>
Rubric


| Possible | Requirement                                                                      |
| -------- | -------------------------------------------------------------------------------- | 
| 3        | A session is created when a user logs in                                         | 
| 3        | New users can register                                                           | 
| 3        | Passwords are hashed and salted                                                  | 
| 3        | Users can log out                                                                | 
| 8        | User can edit and delete their own stories/comments but not those of other users | 
| 4        | Relational database is configure with correct data types and foreign keys        | 
| 3        | Stories can be posted                                                            | 
| 3        | A link can be associated with each story and is stored in its own database field | 
| 4        | Comments can be posted in association with a story                               | 
| 3        | Stories can be edited and deleted                                                |
| 3        | Comments can be edited and deleted                                               | 
| 3        | Code is well formatted and easy to read                                          |
| 2        | Safe from SQL injection attacks                                                  | 
| 3        | Site follows FIEO                                                                | 
| 2        | All pages pass the W3C validator                                                 | 
| 5        | CSRF tokens are passed when creating, editing, and deleting comments/stories     |
| 4        | Site is intuitive to use and navigate                                            | 
| 1        | Site is visually appealing                                                       |  

## Creative Portion (15 possible)

| Feature | 
| ------- |

## Grade

| Total Possible |
| -------------- |
| 75             |
