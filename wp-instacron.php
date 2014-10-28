<?php
/*
Plugin Name: WP InstaCRON
Plugin URI:
Description: Run cron tasks whenever you want to!
Version: 2014.10.28
Author: khromov
Author URI:
*/

class InstaCRON
{
	static function add_job($slug, $function, $params = null)
	{
		add_filter('custom_cron_jobs', function($jobs) use ($slug, $function, $params)
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
			if(($cron_name = trim(get_query_var('custom_cron'))) !== '')
			{
				$jobs = apply_filters('custom_cron_jobs', array());

				//Specific job pointed out, run it
				if(isset($jobs[$cron_name]))
				{
					$jobs[$cron_name][0]($jobs[$cron_name][1]);
				}
				else if($cron_name === 'all')//Else run all jobs
				{
					//Loop over jobs and execute 'em
					foreach($jobs as $name => $job_params_array)
					{
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
			$vars[] = 'custom_cron';
			return $vars;
		});
	}
}

/* Init plugin */
InstaCRON::maybe_run_jobs();