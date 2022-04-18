# Easy charting with Metabase

These past few monthes we've made a brand new iPhone application for [Diwi](https://diwi.com) (dating app in French only, but do take a look!), and as we did a cool start with more than 2K active users in two weeks, we needed insights about who our users are and how they use the app: **basically we needed pie charts**.

## The do it yourself option

When asked for a statistic dashboard, I'm used to do everything myself in a custom backend, but it can actually be a lot of work for just printing several charts:

* Setting routing, views and controllers for your chart dashboard in your backend framework.
* Finding what's the hype graph JavaScript library and install it in your project. Learning the API and the options to make your charts looks nice.
* **Query your database to retrieve the data.**
* Translate the data in your library's custom format.
* Push to git, make a pull request, deploy (-> everytime someone asks you for another chart).

The only real value of your stats is the data, so all the extra work in order to show it is **wasted**. Don't get me wrong, I love doing custom dashboards from scratch, but time is money and when [@maxime](https://twitter.com/maxime) told me about [Metabase](http://www.metabase.com/), although I was very skeptical at first it only took me an afternoon of testing to know this was the solution for us, and after using it for some weeks I've totally fallen in love with it ❤️ (I swear this post is not sponsored and they did not give me any money).

## What you can do with it

[Metabase](http://www.metabase.com/) is just a solution to display your data in various charts, but it does it really well. It can be used as a client or centralized as an hosted application on your server. Here is an example of a dashboard you can make, done in under 15 minutes (these are not real numbers, I'm using a test dataset):

![Metabase dashboard](/img/posts/metabase_dashboard.png)

You have two ways of adding "Questions" to Metabase, first using the GUI:

![Metabase question using the GUI](/img/posts/metabase_question_gui.png)

Or for more complex queries, writing SQL (this means you can do pratically anything given you have the SQL skills):

![Metabase question using SQL](/img/posts/metabase_question_sql.png)

Questions must not necessarily appear on a dashboard, you can add more specific questions to look at later, search them, tag them (with emojis 🙌) etc.

![Metabase questions list](/img/posts/metabase_questions_list.png)

There's a bit more things you can do, like excluding some sensitive information from the mapping and also sending reports at a specific frenquence via e-mail or slack, which can be really convenient. For example you could send a report to your slack channel every morning to inform your team what happened on your website the day before.

## Why you should use Metabase

It's **free** and [open source](https://github.com/metabase/metabase). It's also very polished for an open source project.

It's **easy to try**: download the mac client or use the `jar`, setup it in two minutes linking your database (MySQL, Postgres, Mongo, SQL Server, AWS Redshift, Google BigQuery, Druid, H2 or SQLite), sync it and start making diagrams in literraly 10 minutes. You can even try their demo dataset if you don't have any data at hand.

You can **host it on your server**: plug it to your production database (it's SELECT operations only), add some users, and everyone in your team can view your dashboard or even create their own questions (no SQL knowledge is required).

## What can still be improved

Dear Metabase team/contributors, if you're reading this, thanks for the awesome works you're doing! Metabase is already mature enough to use on a production project.

Still, here is a couple of little things that bugged me out while using Metabase, they might have already been fixed in 0.18 or might be know issues, but here is some things you probably want to know if you want to see if Metabase is for you.

Customization could be improved, for example it would be great to be able to pick individual colours for each data in your charts, even if they were made in SQL. In some projects colours have meanings and I believe it's a great way to improve readability.

It's too bad that in the GUI interface for adding a question, we can only go down to one relation level (For example : User -> Product, not User -> Product -> Category -> etc.), for Diwi's dataset it means I'm forced to write almost every question in SQL, where I could do everything in the GUI if I had an unlimited depth of relation. This also mean that my coworkers cannot easily use the questions I wrote in SQL if they don't have the knowledge.

~~While using the hosted version of Metabase, it looks like I can't directly add a question I just made to a dashboard, I have a `Cannot read property 'display' of undefined` error message and I must go to the question list, hard refresh it before being able to add it in a dashboard, like there is some local cache issue in the browser. Same odd behaviour appears when you make a change to a dashboard, you are forced to hard refresh the page to see the changes you made. This works fine in the Mac client though.~~<br />
Edit: this issue was not at all related to metabase but was because of too much caching by the server!

But nonetheless, the team is doing a really great job, and are adding new features often. For example Metabase 0.18 adds a way to [add global filters to a dashboard](http://www.metabase.com/blog/dashboard-filters) (like a date picker). That feature is reeeeally gold and I can't wait to try it with our data.

![Metabase dashboard filters](http://www.metabase.com/images/dashboard_filters.png)
<p style="text-align:center;font-style:italic;"><small>Dashboard filters example, from Metabase blog</small></p>

## Unnecessary query of doom ☠️

To end this post with a bit of LOL, here is an absolutely not useful query that I made in Metabase.

Here is the deal: remember that Metabase does not have any scripting language, so all you got is your good old SQL. What if you want a graph based based on a date, for example you want to display every new registrations per hour you had in the last 24 hours, you could write something like this:

```sql
SELECT
	DATE_FORMAT(registration_date, '%Y-%m-%d %H') AS "Time",
	COUNT(*) AS "New subscriptions"
FROM user
WHERE DATEDIFF(NOW(), registration_date) <= 1
GROUP BY DATE_FORMAT(registration_date, '%Y-%m-%d %H')
ORDER BY registration_date ASC
```

But what if you don't have any new registrations bewteen 3 and 4 am ? No lines returned means that your graph will not show anything for 3 am, where you want a flat "0 registrations" point in your graph there. We want to add "0" when there is no data during a specific hour.

The key is to have a way to `SELECT` numbers somewhere, then use a date function to substract time from the current date to create our hour range. The thing is there is no range function in SQL, so we have to rely on dirty tricks to generate numbers.

One way would be to add a `numbers` table with  sequential numbers in each row, or to use an incremental index value on one of our existing table (but there should not be any gap in the sequence, and the required amount of rows).

One other trick I found in a [stackoverflow thread](http://stackoverflow.com/questions/27954991/how-to-fill-missing-values-in-mysql-query) is this really cool way of generating a sequence of numbers using powers of two and `CROSS JOIN`:

```sql
SELECT
	TWO_1.SeqValue + TWO_2.SeqValue + TWO_4.SeqValue +
	TWO_8.SeqValue + TWO_16.SeqValue AS number
FROM
	(SELECT 0 SeqValue UNION ALL SELECT 1 SeqValue) TWO_1
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 2 SeqValue) TWO_2
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 4 SeqValue) TWO_4
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 8 SeqValue) TWO_8
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 16 SeqValue) TWO_16
```

This will generate a sequence of numbers from 0 to 31 (enough for our 24h), we only have to integrate it into our query (I also added two other metrics along):

```sql
SELECT
    @date:= DATE_FORMAT(
    	DATE_SUB(
    		NOW(),
    		INTERVAL (
    			TWO_1.SeqValue + TWO_2.SeqValue + TWO_4.SeqValue +
    			TWO_8.SeqValue + TWO_16.SeqValue
    		) HOUR
    	),
    	'%Y-%m-%d %H'
    ) as date,
    (
    	SELECT COUNT(id)
    	FROM user
    	WHERE DATE_FORMAT(registration_date, '%Y-%m-%d %H') = @date
    ) AS "New users",
    (
    	SELECT COUNT(id)
    	FROM offer
    	WHERE DATE_FORMAT(creation_date, '%Y-%m-%d %H') = @date
    ) AS "New rendez-vous",
    (
    	SELECT COUNT(id)
    	FROM diwi
    	WHERE DATE_FORMAT(date, '%Y-%m-%d %H') = @date
    ) AS "New diwi"
FROM
    (SELECT 0 SeqValue UNION ALL SELECT 1 SeqValue) TWO_1
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 2 SeqValue) TWO_2
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 4 SeqValue) TWO_4
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 8 SeqValue) TWO_8
    CROSS JOIN (SELECT 0 SeqValue UNION ALL SELECT 16 SeqValue) TWO_16
GROUP BY date
ORDER BY date ASC
```

Voilà! Hope you enjoyed this useless, unreadable, fun SQL query. It looks like this in Metabase:

![Metabase questions list](/img/posts/metabase_question_doom.png)

Thanks a lot for your attention, do leave me a message if you tried Metabase after reading this post. I hope I'll find the time to write more frequently here!
