<h2 class="page-title">Dashboard</h2>

<div class="dashboard">

    <!-- SUMMARY CARDS -->
    <div class="card-grid">

        <div class="card">
            <div class="card-label">Total Projects</div>
            <div class="card-value"><?php echo $total_projects; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Total Testers</div>
            <div class="card-value"><?php echo $total_testers; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Total Allocations</div>
            <div class="card-value"><?php echo $total_allocations; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Today's Hours</div>
            <div class="card-value"><?php echo $today_hours; ?></div>
        </div>

    </div>


    <!-- TOP PERFORMERS -->
    <div class="section">

        <h3>Top Performers</h3>

        <div class="card-grid">

            <div class="card">
                <div class="card-label">Most Used Project</div>
                <div class="card-value">
                    <?php echo $most_time_project['project_name'] ?? '-'; ?>
                </div>
                <div class="card-sub">
                    <?php echo $most_time_project['total_hours'] ?? 0; ?> hrs
                </div>
            </div>

            <div class="card">
                <div class="card-label">Most Overtime Project</div>
                <div class="card-value">
                    <?php echo $most_overtime_project['project_name'] ?? '-'; ?>
                </div>
                <div class="card-sub">
                    <?php echo $most_overtime_project['overtime_hours'] ?? 0; ?> hrs
                </div>
            </div>

            <div class="card">
                <div class="card-label">Top Billed Tester</div>
                <div class="card-value">
                    <?php echo $most_billed_tester['tester_name'] ?? '-'; ?>
                </div>
                <div class="card-sub">
                    <?php echo $most_billed_tester['billed_hours'] ?? 0; ?> hrs
                </div>
            </div>

            <div class="card">
                <div class="card-label">Top Overtime Tester</div>
                <div class="card-value">
                    <?php echo $most_overtime_tester['tester_name'] ?? '-'; ?>
                </div>
                <div class="card-sub">
                    <?php echo $most_overtime_tester['overtime_hours'] ?? 0; ?> hrs
                </div>
            </div>

        </div>

    </div>


    <!-- TODAY -->
    <div class="section">

        <h3>Today</h3>

        <div class="card-grid">

            <div class="card">
                <div class="card-label">Allocations</div>
                <div class="card-value"><?php echo $today_allocations; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Total Hours</div>
                <div class="card-value"><?php echo $today_hours; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Testers</div>
                <div class="card-value"><?php echo $today_testers; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Overtime</div>
                <div class="card-value"><?php echo $today_overtime; ?></div>
            </div>

        </div>

    </div>


    <!-- WEEK -->
    <div class="section">

        <h3>This Week</h3>

        <div class="card-grid">

            <div class="card">
                <div class="card-label">Allocations</div>
                <div class="card-value"><?php echo $week_allocations; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Total Hours</div>
                <div class="card-value"><?php echo $week_hours; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Testers</div>
                <div class="card-value"><?php echo $week_testers; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Overtime</div>
                <div class="card-value"><?php echo $week_overtime; ?></div>
            </div>

        </div>

    </div>


    <!-- MONTH -->
    <div class="section">

        <h3>This Month</h3>

        <div class="card-grid">

            <div class="card">
                <div class="card-label">Allocations</div>
                <div class="card-value"><?php echo $month_allocations; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Total Hours</div>
                <div class="card-value"><?php echo $month_hours; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Testers</div>
                <div class="card-value"><?php echo $month_testers; ?></div>
            </div>

            <div class="card">
                <div class="card-label">Overtime</div>
                <div class="card-value"><?php echo $month_overtime; ?></div>
            </div>

        </div>

    </div>

</div>


<style>

.page-title {
    margin-bottom: 20px;
}

.dashboard {
    max-width: 1200px;
}

.section {
    margin-top: 30px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
}

.card {
    background: #ffffff;
    border-radius: 6px;
    padding: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid #0052cc;
}

.card-label {
    font-size: 13px;
    color: #6b778c;
}

.card-value {
    font-size: 26px;
    font-weight: bold;
    margin-top: 5px;
    color: #172b4d;
}

.card-sub {
    font-size: 12px;
    color: #6b778c;
    margin-top: 3px;
}

</style>
