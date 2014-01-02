<?php

	require_once('WP_Piwik_Template.php');

	class WP_Piwik_MetaBox_PerPost_Stats extends WP_Piwik_Template {
				
		function addMetabox() {
			add_meta_box(
				'wp-piwik_post_perpoststats',
				__('Piwik Statistics (last 30 days)', 'wp-piwik'),
				array(&$this, 'showStats'),
				'post',
				'side',
				'default'
			);
		}
		
		function showStats() {
			global $post;
			$postURL = get_permalink($post->ID);
			$range = $this->getRangeLast30();
			self::$logger->log('Load per post statistics: '.$postURL);
			$data = self::$wpPiwik->callPiwikAPI('Actions.getPageUrl', 'range', $range, null, false, false, 'PHP', $postURL, false);
			if (!isset($data[0])) return;
			echo '<table>';
			$this->tabRow(__('Visitors', 'wp-piwik').':',$data[0]['nb_visits']);
			$this->tabRow(__('Unique visitors', 'wp-piwik').':', $data[0]['sum_daily_nb_uniq_visitors']);
			$this->tabRow(__('Page views', 'wp-piwik').':', $data[0]['nb_hits']);
			$this->tabRow(__('Time/visit', 'wp-piwik').':', $data[0]['avg_time_on_page']);
			$this->tabRow(__('Bounce count', 'wp-piwik').':', $this->output($data[0], 'entry_bounce_count', 0).' ('.$data[0]['bounce_rate'].')');
			$this->tabRow(__('Exit count', 'wp-piwik').':', $this->output($data[0], 'exit_nb_visits', 0).' ('.$data[0]['exit_rate'].')');
			if (isset($data[0]['avg_time_generation']))
				$this->tabRow(__('Avg. generation time', 'wp-piwik').':', $data[0]['avg_time_generation']);
			echo '</table>';
		}
		
	}