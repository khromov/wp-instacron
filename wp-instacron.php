<?php
/*
Plugin Name: InstaCRON
Plugin URI:
Description: Run cron tasks whenever you want to!
Version: 1.0
Author: khromov
Author URI: http://snippets.khromov.se
GitHub Plugin URI: khromov/wp-instacron
*/

class InstaCRON
{
	static function add_job($slug, $function, $params = null)
	{
		add_filter('instacron_jobs', function($jobs) use ($slug, $function, $params)
		{
			$jobs[$slug] = array($function, $params);
			return $jobs;
		});
	}

	static function maybe_run_jobs()
	{
		/* Hook just before outputting anything */
		add_action('template_redirect', function()
		{
			if(($cron_name = trim(get_query_var(apply_filters('instacron_parameter', 'custom_cron')))) !== '')
			{
				//Grab jobs from filter
				$jobs = apply_filters('instacron_jobs', array());

				//If specific job pointed out, run it
				if(isset($jobs[$cron_name]))
				{
					//Run attached function and pass parameters
					$jobs[$cron_name][0]($jobs[$cron_name][1]);
				}
				else if($cron_name === 'all')//Else run all jobs
				{
					//Loop over jobs and execute 'em
					foreach($jobs as $name => $job_params_array)
					{
						//Run attached function and pass parameters
						$job_params_array[0]($job_params_array[1]);
					}
				}
				else
				{
					echo "No valid job in parameter.";
				}

				//If this was a Custom Cron request, it's time to end it.
				die();
			}
		}, 9999);

		add_filter('query_vars', function($vars)
		{
			$vars[] = apply_filters('instacron_parameter', 'custom_cron');
			return $vars;
		});
	}
}

/* Init plugin */
InstaCRON::maybe_run_jobs();