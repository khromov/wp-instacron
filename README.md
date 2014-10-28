# InstaCRON

InstaCRON is a WordPress plugin that lets you quickly setup tasks (or "jobs") that execute when you want them to by visiting a special URL.

You register your jobs anywhere you'd like in your code (as long as it's on or after the plugins_loaded hook). See examples below.

Please note: You will need a real CRON scheduler to use this plugin. We're planning on providing "fake" CRON using [TLC Transients](https://github.com/markjaquith/WP-TLC-Transients) in the future.

## Create a job

Creating a CRON job can be done in just a few lines of code:

### Example

```php
InstaCRON::add_job('my_custom_job', function() {
    /* Your CRON code here */
});
```

### Passing parameters

```php
InstaCRON::add_job('my_custom_job_with_parameter', function($params)
{
    echo "Hello, " . $params['name']; //Please don't actually echo stuff in your CRON jobs! :-)
}, array('name' => 'Timmy'));
```

### Running CRON jobs

To actually run the CRON you need to schedule a job that grabs the special InstaCRON URL as often as you'd like, which then
immediately runs the code you provided. Use the custom_cron GET variable and set it to the $slug you provided in add_job()

#### Example URL

```
http://site.com/?custom_cron=my_custom_job
```

#### Using CURL

```
curl http://site.com/?custom_cron=my_custom_job >/dev/null 2>&1
```

#### Using UNIX Cron

```
0 * * * * curl http://site.com/?custom_cron=my_custom_job >/dev/null 2>&1
```

(Scheduled once per hour via crontab)


#### Running all CRON jobs

There is an option to run all available cron jobs, simply pass the parameter "all" via the custom_cron GET variable, like this:

```
http://site.com/?custom_cron=all
```

Naturally, you can't use "all" as your $slug for jobs. We don't actually check for it tho', so we're gonna have to use the honor system on this one!

### Conditional loading

If you won't know for sure whether InstaCRON is available, don't forget to check before using the add_job function:

```php
if(class_exists('InstaCRON'))
{
    InstaCRON::add_job('my_custom_job', function()
    {
         /* Your CRON code here */
    });
}
```

### FAQ

#### Can you change the custom_cron GET parameter?

No problem! Use the instacron_parameter filter.

```php
add_filter('instacron_parameter', function($param)
{
    return 'my_param';
});
```

New URL:

```
http://site.com/?my_param=my_custom_job
```

#### PHP Compatibility?

Anything 5.3 and over should work!