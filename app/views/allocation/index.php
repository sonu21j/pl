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

    <div class="jira-header-left">

        <div class="jira-title">
            <?php echo $batch['project_name']; ?>
        </div>

        <div class="jira-sub">
            Date: <?php echo $batch['date']; ?> |
            Shift: <?php echo $batch['shift']; ?>
        </div>

    </div>

    <div class="jira-actions">

        <a class="btn-view"
           href="index.php?url=allocation/details&id=<?php echo $batch['batch_id']; ?>">
           View
        </a>

        <a class="btn-edit"
           href="index.php?url=allocation/edit&id=<?php echo $batch['batch_id']; ?>">
           Edit
        </a>

        <a class="btn-delete"
           href="index.php?url=allocation/delete&id=<?php echo $batch['batch_id']; ?>"
           onclick="return confirm('Delete this allocation?');">
           Delete
        </a>

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

    padding: 16px;
    margin-bottom: 15px;

    border-radius: 8px;

    box-shadow: 0 2px 6px rgba(0,0,0,0.08);

    transition: 0.2s;
}

.jira-card:hover {

    box-shadow: 0 4px 12px rgba(0,0,0,0.15);

}

.jira-header {

    display: flex;
    justify-content: space-between;
    align-items: center;

}

.jira-title {

    font-size: 16px;
    font-weight: bold;
    color: #172b4d;

}

.jira-sub {

    font-size: 13px;
    color: #6b778c;

}

.jira-summary {

    margin-top: 8px;
    font-size: 13px;
    color: #172b4d;

}

.jira-row {

    padding: 6px 0;
    border-top: 1px solid #eee;

}

.jira-actions a {

    margin-left: 8px;
    text-decoration: none;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;

}

.btn-view {

    background: #0052cc;
    color: white;

}

.btn-edit {

    background: #36b37e;
    color: white;

}

.btn-delete {

    background: #de350b;
    color: white;

}

.billing-billed {

    color: #36b37e;
    font-weight: bold;

}

.billing-overtime {

    color: #de350b;
    font-weight: bold;

}

.billing-unbilled {

    color: #ff8b00;
    font-weight: bold;

}


</style>
