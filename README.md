# WP InstaCRON

WP InstaCRON is a WordPress plugin that lets you quickly setup CRON tasks that execute when you want them to by visiting a special URL.

You register your jobs anywhere you'd like (as long as it's after the plugins_loaded hook). See examples below.

## Create a cron task

Creating a CRON task can be done in just a few lines of code

### Simple example

```php
InstaCRON::add_job('my_custom_job', function() {
    /* Your CRON code here */
});
```

### Passing parameters

```php
InstaCRON::add_job('my_custom_job_with_parameter', function($params)
{
    echo "Hello, " . $params['name'];
}, array('name' => 'Timmy'));
```


### Running CRON jobs

To actually run the CRON you need to schedule a task that grabs the special InstaCRON URL as often as you'd like.
Use the custom_cron GET variable and set it to the $slug you provided in add_job()

#### Example

```
http://myfeed.idg.se/?custom_cron=my_custom_job
```

#### Running all CRON jobs

There is an option to run all available cron jobs, simply pass the parameter "all" to custom_cron

```
http://myfeed.idg.se/?custom_cron=my_custom_job
```

Naturally, you can't use "all" as your $slug. We don't actually check for it tho', so we're gonna have to use the honor system on this one!

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