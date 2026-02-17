<?php 
$title = "Bulk Allocation";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<style>
    /* Custom styles for this advanced form */
    .section-box { border: 1px solid #dfe1e6; background: #fff; padding: 20px; border-radius: 3px; margin-bottom: 20px; }
    .section-title { font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px; color: #0052cc; }
    
    /* Date Pills */
    .date-pill { display: inline-block; background: #ebecf0; border-radius: 3px; padding: 4px 8px; margin-right: 5px; margin-bottom: 5px; font-size: 13px; }
    .date-pill .remove-date { margin-left: 5px; cursor: pointer; color: #ff5630; font-weight: bold; }
    
    /* Tester Table */
    .tester-row { display: flex; gap: 10px; align-items: center; border-bottom: 1px solid #eee; padding: 10px 0; background: #fafbfc; }
    .tester-row:nth-child(even) { background: #fff; }
    .tester-row input, .tester-row select { padding: 5px; font-size: 13px; width: 100%; border: 1px solid #dfe1e6; border-radius: 3px; }
    
    /* Cols width control */
    .col-tester { flex: 2; }
    .col-platform { flex: 1; }
    .col-type { flex: 1; }
    .col-hours { width: 80px; }
    .col-action { width: 40px; text-align: center; }

    .btn-outline { background: white; border: 1px solid #dfe1e6; color: #172b4d; }
    .btn-remove { background: #ffebe6; color: #de350b; border: none; width: 30px; height: 30px; border-radius: 50%; font-weight: bold; cursor: pointer; }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Create Bulk Allocation</h2>
        <a href="/allocations" class="btn btn-outline">Back</a>
    </div>

    <form action="/allocations/store_bulk" method="POST" id="bulkForm">
        
        <!-- SECTION 1: HEADER info -->
        <div class="section-box">
            <div class="section-title">1. Allocation Details</div>
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex:1;">
                    <label>Allocation Group Name / Reference ID</label>
                    <input type="text" name="allocation_name" placeholder="e.g. Project X - Nov Testing Phase" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Project</label>
                    <select name="project_id" required>
                        <option value="">-- Select Project --</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['project_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DATES -->
        <div class="section-box">
            <div class="section-title">2. Select Dates</div>
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex-grow: 1;">
                    <label>Pick Date to Add</label>
                    <input type="date" id="dateInput" style="padding: 8px;">
                </div>
                <button type="button" class="btn btn-outline" onclick="addDate()">+ Add Date</button>
            </div>
            
            <div id="dateContainer" style="margin-top: 15px; min-height: 40px; padding: 10px; border: 1px dashed #ccc; border-radius: 3px;">
                <span style="color: #999; font-style: italic; font-size: 13px;" id="noDatesMsg">No dates selected</span>
            </div>
            
            <!-- Hidden Input stores value "2023-10-01,2023-10-02" for PHP -->
            <input type="hidden" name="selected_dates_string" id="selectedDatesString" required>
        </div>

        <!-- SECTION 3: TESTERS -->
        <div class="section-box">
            <div class="section-title">3. Add Resources (Testers)</div>
            
            <div id="testerHeader" style="display: flex; gap: 10px; font-weight: bold; font-size: 12px; color: #5e6c84; margin-bottom: 10px; padding-left: 5px;">
                <div class="col-tester">Tester</div>
                <div class="col-platform">Platform</div>
                <div class="col-type">Billing Type</div>
                <div class="col-hours">Hours</div>
                <div class="col-action"></div>
            </div>

            <div id="testersContainer">
                <!-- Javascript will put rows here -->
            </div>

            <button type="button" class="btn btn-outline" onclick="addTesterRow()" style="width: 100%; margin-top: 15px; border-style: dashed;">+ Add Another Tester</button>
        </div>

        <!-- ACTIONS -->
        <div style="text-align: right; margin-top: 20px;">
            <a href="/allocations" class="btn btn-outline" style="margin-right: 10px; padding: 10px 20px;">Cancel</a>
            <button type="button" onclick="validateAndSubmit()" class="btn" style="padding: 10px 20px;">Save Allocation</button>
        </div>

    </form>
</div>

<!-- DATA STORE for JS -->
<script>
    const allTesters = <?= json_encode($testers) ?>;
    const selectedDates = new Set();
    let testerIndex = 0;

    // --- DATE LOGIC ---
    function addDate() {
        const input = document.getElementById('dateInput');
        const val = input.value;
        if(!val) return;
        
        if(!selectedDates.has(val)) {
            selectedDates.add(val);
            renderDates();
        }
        input.value = ''; // clear input
    }

    function removeDate(date) {
        selectedDates.delete(date);
        renderDates();
    }

    function renderDates() {
        const container = document.getElementById('dateContainer');
        const hiddenInput = document.getElementById('selectedDatesString');
        const msg = document.getElementById('noDatesMsg');

        // Reset
        let html = '';
        if(selectedDates.size === 0) {
            msg.style.display = 'block';
        } else {
            msg.style.display = 'none';
            // Sort dates
            const sorted = Array.from(selectedDates).sort();
            sorted.forEach(d => {
                html += `<div class="date-pill">${d} <span class="remove-date" onclick="removeDate('${d}')">&times;</span></div>`;
            });
            // Update hidden input
            hiddenInput.value = sorted.join(',');
        }
        
        // Use a temp wrapper to append without wiping the message div existence completely logic
        const pillsDiv = document.createElement('div');
        pillsDiv.innerHTML = html;
        
        // Clear non-message nodes
        Array.from(container.children).forEach(c => {
            if(c.id !== 'noDatesMsg') container.removeChild(c);
        });
        
        container.appendChild(pillsDiv);
    }

    // --- TESTER LOGIC ---
    function addTesterRow() {
        const container = document.getElementById('testersContainer');
        const index = testerIndex++;

        // Build Tester Options
        let testerOptions = '<option value="">Select Tester...</option>';
        allTesters.forEach(t => {
            testerOptions += `<option value="${t.id}">${t.first_name} ${t.last_name}</option>`;
        });

        const rowHtml = `
            <div class="tester-row" id="row-${index}">
                <div class="col-tester">
                    <select name="testers[${index}][id]" required>
                        ${testerOptions}
                    </select>
                </div>
                <div class="col-platform">
                    <input type="text" name="testers[${index}][platform]" placeholder="e.g. PC/iOS" required value="PC">
                </div>
                <div class="col-type">
                    <select name="testers[${index}][billing]">
                        <option value="Billed">Billed</option>
                        <option value="Unbilled">Unbilled</option>
                        <option value="Overtime">Overtime</option>
                    </select>
                </div>
                <div class="col-hours">
                    <input type="number" name="testers[${index}][hours]" value="8" step="0.5" required>
                </div>
                <div class="col-action">
                    <button type="button" class="btn-remove" onclick="removeTesterRow(${index})">&times;</button>
                </div>
            </div>
        `;
        
        // Append
        const wrapper = document.createElement('div');
        wrapper.innerHTML = rowHtml;
        container.appendChild(wrapper.firstChild);
    }

    function removeTesterRow(index) {
        const row = document.getElementById('row-' + index);
        row.remove();
    }

    function validateAndSubmit() {
        if(selectedDates.size === 0) {
            alert("Please add at least one date.");
            return;
        }
        // Basic check for tester rows
        const container = document.getElementById('testersContainer');
        if(container.children.length === 0) {
            alert("Please add at least one tester.");
            return;
        }
        document.getElementById('bulkForm').submit();
    }

    // Initialize one row by default
    addTesterRow();
</script>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>