## TOTAL TIME

About 12 hours.

I spent more time because it was my first time with the Symfony framework. Most of the time went into learning the documentation and the framework structure.

## Summary of work

1. Added Category entity and relation between books and categories. Then I ran migration with php bin/console make:migration and php bin/console doctrine:migrations:migrate.
   Added CRUD with php bin/console make:crud.
   For category delete I added a check in BookCategoryController — category can be deleted only if it is not linked to books. For unsuccessful deletion i added flash.
   Added categories faker to fixtures

2. To resolve problem with book description length I found 80 symbols limit in code and deletee/commented this places.