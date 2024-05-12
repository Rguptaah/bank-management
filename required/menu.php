<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="image float-left">
				<img src="images/user2-160x160.jpg" class="rounded-circle" alt="User Image">
			</div>
			<div class="info float-left">
				<p>Welcome <?php echo $user_name; ?></p> <a href="#"><i
						class="fa fa-circle text-success"></i><?php echo $user_type; ?></a>
			</div>
			<form action="collect_fee" method="get" class="sidebar-form">
				<input name="student_class" type='hidden'>
				<input name="search_by" type='hidden' value='student_name'>
				<!-- <div class="input-group">
					<input type="text" name="search_text" class="form-control" id='search_text'
						placeholder="Student Name"> <span class="input-group-btn"> <button type="submit" name="search"
							id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> </button> </span>
				</div> -->
			</form>
		</div>
		<ul class="sidebar-menu" data-widget="tree" id='nav'>
			<li>
				<a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard</a>
			</li>
			<li class="treeview">
				<a href="#"> <i class="fa fa-user-circle"></i> <span>Members</span> <span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i> </span>
				</a>
				<ul class="treeview-menu">
					<li><a href="add_member">Add Member</a></li>
					<li><a href="manage_member">Manage Member</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"> <i class="fa fa-dollar"></i> <span>Deposits</span> <span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i> </span>
				</a>
				<ul class="treeview-menu">
					<li><a href="add_member">Fixed Deposit (FD)</a></li>
					<li><a href="manage_member">Recurring Deposit (RD)</a></li>
					<li><a href="deposits">Deposit Report</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"> <i class="fa fa-list"></i> <span>Plans</span> <span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i> </span>
				</a>
				<ul class="treeview-menu">
					<li><a href="add_plan">Add Plan</a></li>
					<li><a href="manage_plans">Manage Plan</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#"> <i class="fa fa-user"></i> <span>Users</span> <span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i> </span>
				</a>
				<ul class="treeview-menu">
					<li><a href="add_user">Add User</a></li>
					<!-- <li><a href="manage_user">Manage User</a></li> -->
				</ul>
			</li>
		</ul>
	</section>
	<div class="sidebar-footer">
		<a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Settings" id='appinfo'><i
				class="fa fa-cog fa-spin"></i></a>
		<a href="support" class="link" data-toggle="tooltip" title="" data-original-title="Help & Support"><i
				class="fa fa-life-ring" aria-hidden="true"></i></i></a>
		<a href="#" class="link" data-toggle="tooltip" title="" onclick="logout()"><i class="fa fa-power-off"></i></a>
	</div>
</aside>