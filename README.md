> :bulb: This project was made in roughly 75h using **WAMP stack + Twig, PostCSS and a bit of JS**.
This is a "coding assessment" from my 1st coding class of the year.

---

# Features
- User login
- God/Admin status
- Dashboard with all articles and the user's personal articles.
- Comment an article (even if anonymous)
- Access, create, update and delete articles
- Delete a comment

Which can be summed up this way:

|         | Create | Read | Update | Delete |
|---------|--------|------|--------|--------|
| Article | x      | x    | x      | x      |
| Comment | x      | x    |        | x      |


---

# Get started
> :warning: `composer` and `npm` are required to run this project.

- Clone this project and go to its folder.
- In your terminal, run the following commands:

```
composer install && npm install
```

This will install all the dependencies of the project.

- Go to the `database` folder. Dump the data in `seed.sql` in your database administration tool, like PHPMyAdmin, DBeaver, **whatever**.

- In your terminal, run the following command:
```
npm run prod
```

This will process all the assets (minify CSS and JS, optimize images...).

**In your browser, hit `localhost`. You should see the blog, all shiny and pretty :sparkles:**

- Now you can test different user profiles. Here's some logs:

|          | Anonymous | Writer              | Admin          |
|----------|-----------|---------------------|----------------|
| mail     |           | jeantester@mail.com | admin@mail.com |
| password |           | test                | password       |

Enjoy!

- If you wish to add more features, run `npm start` in your terminal, hit your localhost and turn your Livereload extension on. Yay, magic!

### **:checkered_flag: That's all folks!**
