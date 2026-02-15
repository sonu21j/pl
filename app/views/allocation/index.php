<h2>Allocations</h2>

<a href="index.php?url=allocation/create">
	<button>Create Allocation</button>
</a>

<br><br>
<h3>Filters</h3>

<form method="get">

<input type="hidden" name="url" value="allocation/index">

Project:

<select name="project_id">

<option value="">All</option>

<?php foreach($projects as $project): ?>

<option value="<?php echo $project['id']; ?>">

<?php echo $project['project_name']; ?>

</option>

<?php endforeach; ?>

</select>


Date From:

<input type="date" name="date_from">


Date To:

<input type="date" name="date_to">


<button type="submit">
Filter
</button>

</form>

<br>

<br>

<?php foreach($allocations as $batch): ?>

<div class="jira-card">

    <div class="jira-header">

        <div class="jira-title">
            <?php echo $batch['project_name']; ?>
        </div>

        <div class="jira-sub">

            Date: <?php echo $batch['date']; ?> |
            Shift: <?php echo $batch['shift']; ?>

        </div>

    </div>


    <div class="jira-summary">

        Platforms: <?php echo $batch['total_platforms']; ?> |

        Testers: <?php echo $batch['total_testers']; ?> |

        Total Hours: <?php echo $batch['total_hours']; ?> |

        OT: <?php echo $batch['total_ot']; ?>

    </div>


    <div class="jira-testers">

        <?php foreach($batch['testers'] as $tester): ?>

            <div class="jira-row">

                <b>
                <?php echo $tester['first_name']." ".$tester['last_name']; ?>
                </b>

                |

                <?php echo $tester['hours']; ?>h

                |

                <?php echo $tester['platform']; ?>

                |

                <span class="billing-<?php echo strtolower($tester['billing_type']); ?>">
                    <?php echo $tester['billing_type']; ?>
                </span>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php endforeach; ?>

<style>

.jira-card {

    background: #ffffff;

    border-left: 4px solid #0052cc;

    padding: 15px;

    margin-bottom: 15px;

    border-radius: 6px;

    box-shadow: 0 2px 5px rgba(0,0,0,0.1);

}

.jira-title {

    font-size: 16px;
    font-weight: bold;

}

.jira-sub {

    font-size: 13px;
    color: #6b778c;
    margin-top: 3px;

}

.jira-summary {

    margin-top: 8px;
    font-size: 13px;

}

.jira-row {

    margin-top: 5px;
    padding-top: 5px;
    border-top: 1px solid #eee;

}

.billing-billed {
    color: green;
}

.billing-overtime {
    color: red;
}

.billing-unbilled {
    color: orange;
}

</style>
