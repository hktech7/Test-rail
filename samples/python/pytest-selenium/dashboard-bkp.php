<?php
$header = [
	'page_title' => lang('dashboard_title'),
	'page_backlink' => false,
	'page_menu' => 'global',
	'page_selected_menu' => 'dashboard',
	'page_breadcrumb' => [
		lang('pages_dashboard')
	]
];

$temp = [];
$temp['display'] = $display;
$header['availableProjects'] = $availableProjects;
$header['selectedProjectIds'] = $selectedProjectIds;
$header['chartStartDate'] = $chartStartDate;
$header['chartEndDate'] = $chartEndDate;
$header['entity'] = $entity;
$header['chart_variable'] = $chart_variable;
$header['searchTerms'] = $searchTerms;
$header['is_admin'] = $is_admin;
$header['can_add'] = $can_add;
$header['active'] = $active;
$header['completed_count'] = $completed_count;
$header['completed'] = $completed;
$header['show_banner'] = $show_banner;
$header['hide_menu'] = true;
$header['page_headline'] = $GI->load->view('cross_project_reports/dashboard/headline', $temp, true);
$header['page_side_bar'] = $GI->load->view('dashboard/sidebar', $header, true);
$header['is_dashboard'] = true;
$header['cross_project_sidebar'] = $GI->load->view('cross_project_reports/sidebar_plugins', $temp, true);
$GI->load->view('cross_project_reports/dashboard/header', $header);
?>

<div class="tabs">
	<div class="tab-header">
		<a href="javascript:void(0)" class="tab1 current" rel="1" onclick="App.Tabs.activateDashboardTabs(this, 'all')"><?= lang('dashboard_all_projects') ?></a>
		<?php if (bits::is_set($permissions, TP_REPORT_CROSS_PROJECT_REPORTS) && $is_enterprise && $role_id !== TP_NOACCESS_ID): ?>
            <a href="javascript:void(0)" class="tab2" rel="2" onclick="App.Tabs.activateDashboardTabs(this, 'cross')"><?= lang('cross_project_reports') ?></a>
		<?php endif ?>
	</div>
	<div class="tab-body tab-frame">
		<div class="tab tab1">
			<?php if ($can_show_update): ?>
				<?php
				$temp = [];
				$temp['latest_version'] = $latest_version;
				$temp['installed_version'] = $installed_version;
				$GI->load->view('projects/update_check', $temp);
				?>
			<?php endif ?>

			<?php if ($can_show_expiration): ?>
				<?php
				$temp = [];
				$temp['support_expired'] = $support_expired;
				$temp['support_expired_on'] = $support_expired_on;
				$GI->load->view('projects/support_expired', $temp);
				?>
			<?php endif ?>

			<?php
			$GI->load->view('admin/projects/add_dialog');
			?>

			<?php $favs_lookup = obj::get_lookup($favs) ?>

			<?php $has_favs = count($favs) > 0 ?>
			<?php $has_active = count($active) > 0 ?>
			<?php $has_completed = count($completed) > 0 ?>
			<?php $has_projects = $has_favs || $has_active || $has_completed ?>

			<?php if (!$has_projects): ?>
				<?php if ($is_admin): ?>
					<div class="empty empty-with-explanation">
						<div class="empty-explanation">
							<div class="empty-explanation-title"><?= lang('dashboard_empty_admin_expl_title') ?></div>
							<div class="empty-explanation-body"><?= lang('dashboard_empty_admin_expl_body') ?></div>
						</div>
						<div class="empty-content empty-info">
							<div class="empty-title" data-testid="dashboardEmptyTitle"><?= lang('dashboard_empty_admin_title') ?></div>
							<div class="empty-body">
								<p><?= lang('dashboard_empty_admin_body') ?></p>

								<div class="button-group">
									<a id="navigation-empty-addproject" href="<?= url::site('admin/projects/add/1') ?>"
										class="button button-left button-add">
										<?= lang('projects_new') ?>
									</a>
									<a id="navigation-empty-addexampleproject" data-testid="navigationEmptyAddexampleproject" <?= js::link('App.Admin.addExampleProject()') ?>
										class="button button-left button-add">
										<?= lang('projects_new_example') ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php else: ?>
					<div class="empty empty-with-explanation">
						<div class="empty-explanation">
							<div class="empty-explanation-title"><?= lang('dashboard_empty_user_expl_title') ?></div>
							<div class="empty-explanation-body"><?= lang('dashboard_empty_user_expl_body') ?></div>
						</div>
						<div class="empty-content empty-info">
							<div class="empty-title"><?= lang('dashboard_empty_user_title') ?></div>
							<div class="empty-body">
								<p><?= lang('dashboard_empty_user_body') ?></p>
							</div>
						</div>
					</div>
				<?php endif ?>
			<?php else: ?>
				<?php
				$GI->load->view('charts/defaults');
				?>


				<div id="actionContainer">
				<?php
				$temp = [];
				$temp['actions'] = $actions;
				$temp['custom_field'] = $custom_field;
				$temp['projects'] = $projects;
				$temp['availableProjects'] = $availableProjects;
				$temp['selectedProjectIds'] = $selectedProjectIds;
				$temp['startDate'] = $chartStartDate;
				$temp['endDate'] = $chartEndDate;
				$temp['days'] = $actionDays;
				$temp['entity'] = $entity;
				$temp['chart_type'] = $chart_type;
				$temp['project_id'] = isset($active[0]) ? $active[0]->id : 0;
				$temp['datapointsTotal'] = $datapointsTotal;
				$temp['chart_variable'] = $chart_variable;
				$temp['is_multi_series'] = $is_multi_series;
				$temp['legends'] = $legends;
				$GI->load->view('dashboard/actions', $temp);
				?>
				</div>
				<div id="drilldown_container"></div>

					<div id="content_container">

					<h1 class="top"><?= lang('dashboard_projects') ?></h1>

					<div id="favs" class="<?= !$has_favs ? 'hidden' : '' ?>">
					<?php
					$temp = [];
					$temp['projects'] = $favs;
					$temp['favs_lookup'] = $favs_lookup;
					$temp['idspace'] = 'fav';
					$GI->load->view('projects/table', $temp);
					?>
					</div>

					<?php if ($has_active): ?>
						<h2 id="activeHeader" class="<?= !$has_favs ? 'hidden' : '' ?>">
							<?= lang('dashboard_active') ?>
						</h2>
						<?php if ($display == 'large'): ?>
							<?php
							$temp = [];
							$temp['projects'] = $active;
							$temp['favs_lookup'] = $favs_lookup;
							$GI->load->view('projects/table', $temp);
							?>
						<?php else: ?>
							<?php
							$temp = [];
							$temp['projects'] = $active;
							$temp['favs_lookup'] = $favs_lookup;
							$temp['show_star'] = true;
							$temp['show_icon'] = true;
							$temp['show_details'] = true;
							$GI->load->view('projects/list', $temp);
							?>
						<?php endif ?>

					<?php else: ?>
						<?php if ($is_admin): ?>
							<p style="margin: 0"><?= langf('dashboard_no_active_admin',
								url::site('admin/projects/overview')) ?></p>
						<?php else: ?>
							<p style="margin: 0"><?= lang('dashboard_no_active') ?></p>
						<?php endif ?>
					<?php endif ?>

					<?php if ($has_completed): ?>
						<h2 style="<?= (!$has_active && !$has_favs) ? 'margin-top: 1.5em' : '' ?>">
							<?= lang('dashboard_completed') ?>
						</h2>

						<div id="completed">
						<?php
						$temp = [];
						$temp['projects'] = $completed;
						$temp['favs_lookup'] = $favs_lookup;
						$temp['show_star'] = true;
						$temp['show_completed'] = true;
						$temp['show_icon'] = true;
						$GI->load->view('projects/list', $temp);
						?>
						</div>

						<?php if ($completed_count > count($completed)): ?>
							<div id="showCompleted" style="margin-top: 0.5em">
								<span class="showAll text-secondary pull-right">
									<a id="navigation-projects-showall" class="link" <?= js::link('App.Projects.loadCompleted()') ?>>
										<?= lang('layout_actions_showall') ?></a>
								</span>
								<span class="busy pull-right">
									<div class="icon-progress-inline"></div>
								</span>
							</div>
						<?php endif ?>
					<?php endif ?>
				</div>
			<?php endif ?>
		</div>
		<div class="tab tab2" style="display:none;">
			<?php $has_shared = count($shared) > 0 ?>
			<?php $has_private = count($private) > 0 ?>
			<?php $has_jobs = count($jobs) > 0 ?>
			<?php $has_templates = count($templates) > 0 ?>

			<?php
			$can_delete = permissions::checkRows(TP_ACTION_REPORTS_DELETE, [...$shared, ...$private]);
			$can_delete_jobs = permissions::checkRows(TP_ACTION_REPORT_JOBS_DELETE, $jobs);
			?>

			<?php if ($has_jobs): ?>
				<?php $has_jobs = false ?>
				<?php foreach ($jobs as $job): ?>
					<?php if ($job->created_by == $user_id || $is_admin): ?>
						<?php $has_jobs = true ?>
						<?php break ?>
					<?php endif ?>
					<?php $system_options = $job->system_options ?>
					<?php $access = arr::get($system_options, 'access');
					if ($access == TP_REPORTS_ACCESS_SHARED): ?>
						<?php $has_jobs = true ?>
						<?php break ?>
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>

			<?php if ($has_templates): ?>
				<?php $has_templates = false ?>
				<?php foreach ($templates as $template): ?>
					<?php if ($template->created_by == $user_id || $is_admin): ?>
						<?php $has_templates = true ?>
						<?php break ?>
					<?php endif ?>
					<?php $system_options = $template->system_options ?>
					<?php $access = arr::get($system_options, 'access');
					if ($access == TP_REPORTS_ACCESS_SHARED): ?>
						<?php $has_templates = true ?>
						<?php break ?>
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>

			<?php $has_reports = $has_shared || $has_private || $has_jobs || $has_templates ?>

			<?php if (!$has_reports): ?>
				<div class="empty empty-with-explanation-cross-project">
					<div class="empty-explanation empty-explanation-cross-project">
						<div class="empty-explanation-title-cross-project"><?= lang('cross_project_empty_title') ?></div>
						<div class="empty-explanation-body empty-explanation-body-cross-project"><?= lang('cross_project_empty_content') ?></div>
					</div>
					<div class="empty-info-cross-project">
						<div class="empty-title-cross-project"><?= lang('cross_project_empty_info_title') ?></div>
						<div class="empty-body-cross-project-list"><?= lang('cross_project_empty_info_content') ?></div>
					</div>
				</div>
			<?php else: ?>
				<?php $task = task::get() ?>
				<?php $has_task = $task && $task->is_installed ?>
				<?php if (!$has_task): ?>
				<?php
				$GI->load->view('cross_project_reports/overview_no_task');
				?>
				<?php endif ?>
				<?php if (!empty($can_delete_jobs) || !empty($can_delete)): ?>
					<span class="delete-selected-link delete-selected" id="cross-delete-reports" data-testid="crossBulkDeleteReportsButton">
						<a onclick="App.Reports.deleteBulkReport(this, 'cross_project_reports');" href="javascript:void(0);"
							class="button button-right button-negative button-cancel margin-bottom-10 ">
							<?= lang('report_bulk_delete') ?>
						</a>
					</span>
				<?php endif; ?>
				<?php if ($has_shared): ?>
					<h1 class="top width-4">
						<?= lang('reports_overview_shared') ?>
					</h1>
					<div id="crossShared" class="report-title">
						<?php
							$GI->load->view('cross_project_reports/groups',
								[
									'show_user' => true,
									'reports' => $shared,
									'user_id' => $user_id
								]
							);
						?>
					</div>

					<?php if ($shared_count > $shared_count_partial): ?>
						<span class="pagination-report">
							<span id="sharedPaginationBusy" class="hidden"><div class="icon-progress-inline"></div></span>
							<span id="sharedPagination">
							<?php
								$GI->load->view(
									'cross_project_reports/pagination',
									[
										'report_count' => $shared_count,
										'report_count_partial' => $shared_count_partial,
										'is_private' => false,
										'offset' => 0
									]
								);
							?>
							</span>
						</span>
					<?php endif ?>
				<?php endif ?>

				<?php if ($has_private): ?>
					<h1 class="<?= !$has_shared ? 'top' : '' ?> width-4">
						<?= lang('reports_overview_personal') ?>
					</h1>
					<div id="crossPrivate" data-testid="privateReport" class="report-title">
						<?php
							$GI->load->view('cross_project_reports/groups',
								[
									'reports' => $private,
									'user_id' => $user_id
								]
							);
						?>
					</div>

					<?php if ($private_count > $private_count_partial): ?>
						<span class="pagination-report">
							<span id="privatePaginationBusy" class="hidden"><div class="icon-progress-inline"></div></span>
							<span id="privatePagination">
							<?php
								$GI->load->view(
									'cross_project_reports/pagination',
									[
										'report_count' => $private_count,
										'report_count_partial' => $private_count_partial,
										'is_private' => true,
										'offset' => 0
									]
								);
							?>
							</span>
						</span>
					<?php endif ?>
				<?php endif ?>
				<?php if ($has_jobs): ?>
					<div id="crossJobs" class="report-header">
						<?php if (!empty($can_delete_jobs)): ?>
							<span class="report-input"><input type="checkbox" onchange="SelectAllReports(this)" name="select_all" data-testid="reportSelectAllCheckbox"></span>
						<?php endif; ?>
						<h1 data-testid="deleteScheduledEeport"><?= lang('reports_overview_scheduled') ?></h1>
						<?php
							$GI->load->view('cross_project_reports/jobs/grid',
								[
									'user_id' => $user_id,
									'show_icon' => true,
									'jobs' => $jobs,
									'is_admin' => $is_admin
								]
							);
						?>
					</div>
				<?php endif ?>
				<?php if ($has_templates): ?>
					<div id="crossTemplates" class="report-header">
						<?php if (!empty($can_delete_jobs)): ?>
							<span class="report-input"><input type="checkbox" onchange="SelectAllReports(this)" name="select_all" data-testid="reportSelectAllCheckbox"></span>
						<?php endif; ?>
						<h1 id="reports-overview-on-demand-via-api-header" data-testid="deleteApiTemplateReport">
							<?= lang('reports_overview_on_demand_via_api') ?>
						</h1>
						<?php
							$GI->load->view('cross_project_reports/templates/grid',
								[
									'user_id' => $user_id,
									'show_icon' => true,
									'templates' => $templates,
									'is_admin' => $is_admin
								]
							);
						?>
					</div>
				<?php endif ?>
			<?php endif ?>

			<?php $in_progress_ids = [] ?>
			<?php foreach ($shared as $report): ?>
				<?php if ($report->status == TP_REPORTS_STATUS_UNPROCESSED): ?>
					<?php $in_progress_ids[] = $report->id ?>
				<?php endif ?>
			<?php endforeach ?>
			<?php foreach ($private as $report): ?>
				<?php if ($report->status == TP_REPORTS_STATUS_UNPROCESSED): ?>
					<?php $in_progress_ids[] = $report->id ?>
				<?php endif ?>
			<?php endforeach ?>

			<?php if ($in_progress_ids): ?>
			<script type="text/javascript">
			$(document).ready(
				function()
				{
					App.Reports.applyProgressCheck(
						<?= json::encode($in_progress_ids) ?>,
						'cross_project_reports'
					);
				}
			);
			</script>
			<?php endif ?>
			<script type="text/javascript">
				$('.delete-selected').hide();
				function SelectAllReports(report_type) {
					let checked_status = $(report_type).is(':checked');
					let parent = '#' + $(report_type).parent().closest('div').attr('id');
					$(parent)
						.find('input[type=checkbox]')
						.not(':disabled')
						.prop(
							'checked',
							checked_status
						);
					$('.delete-selected').css(
						'display',
						$('input:checked[name="report"]').length
							? 'block'
							: 'none'
					);
				}

				$('#content').on('change', 'input:checkbox[name="report"]', function() {
					let parent = '#' + $(this).parent().closest('div').attr('id');
					$(parent)
						.find('input:checkbox[name=select_all]')
						.prop(
							'checked',
							$(parent + ' input:checkbox[name="report"]').length
								=== $(parent + ' input:checked[name="report"]').length
						);
					$('.delete-selected').css(
						'display',
						$('input:checked[name="report"]').length > 0
							? 'block'
							: 'none'
					);
				});
			</script>
			<?php
			$temp = [];
			$temp['users'] = $users;
			$GI->load->view('cross_project_reports/share_dialog', $temp);
			?>

			<!-- Since content was closed in middle.php and now it is
			moved to header.php so content blocks are closed here -->
			</div>
			</div>

			<?php
			$GI->load->view('layout/middle');
			$GI->load->view(
				'dialogs/bulk_delete',
				[
					'message' => lang('report_bulk_delete_message'),
					'confirmation' => lang('report_bulk_delete_confirmation')
				]
			);
			?>
		</div>
	</div>
</div>

<!-- Since content was closed in middle.php and now it is
moved to header.php so content blocks are closed here -->
</div>
</div>

<?php
$GI->load->view('layout/middle');
?>

<?php
$temp = [];
$temp['page_show_announcement'] = true;
$temp['page_goals_start'] = !$has_projects;
$GI->load->view('layout/footer', $temp);
?>


<script>
	$(window).ready(function() {
		const updateUrl = (hash = '') => {
			const url = new URL(window.location.href);
			url.hash = hash;
			window.history.replaceState({}, document.title, url.toString());
		}

		App.Tabs.activateDashboardTabs = (e, name) => {
			App.Tabs.activate(e);

			if (name === 'all') {
				document.title = '<?= lang('cross_project_all_project_title') ?>';
				updateUrl();

				$('#all-projects-sidebar').show();
				$('#cross-projects-sidebar').hide();
				$('.content-header-icon').show();
			} else {
				document.title = '<?= lang('cross_project_cross_project_title') ?>';
				updateUrl('cross_project_reports');

				$('#all-projects-sidebar').hide();
				$('#cross-projects-sidebar').show();
				$('.content-header-icon').hide();
			}
		}

		if (window.location.hash === '#cross_project_reports') {
			App.Tabs.activateDashboardTabs($('.tab2'), 'cross');
		} else {
			App.Tabs.activateDashboardTabs($('.tab1'), 'all');
		}
	});
</script>

<style>
    .content-inner {
        padding: 18px 0px 30px 0px;
    }
    .tab-frame {
        border: 0px;
    }
    .tab-header>a {
        height: 25px;
        line-height: 15px;
    }
    .sidebar-h1::after {
        border-bottom: 0px solid #AECADE;
    }
    .grid td.darkSelected, .grid tr.dark:hover {
        background: #ffffff;
    }
</style>
