<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Planning with Rich Text Editor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-body {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        .add_head {
            color: #3B71CA;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .btn-add {
            background-color: #3B71CA;
            transition: all 0.3s;
        }
        .btn-add:hover {
            background-color: #2b5da3;
            transform: translateY(-2px);
        }
        .remove-day {
            height: fit-content;
        }
        .editor-toolbar {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .editor-content {
            min-height: 120px;
            border: 1px solid #ced4da;
            border-radius: 0 0 8px 8px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .editor-content:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .toolbar-btn {
            background: none;
            border: none;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
        }
        .toolbar-btn:hover {
            background-color: #e9ecef;
        }
        .day-block {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #3B71CA;
        }
        .rte-container {
            margin-top: 10px;
        }
        .editor-content img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <div class="form-body rounded-4">
                    <h4 class="add_head fw-bold mb-3">3. Tour Planning</h4>
                    <div id="day-wrapper">
                        <div class="day-block">
                            <div class="row g-2 mb-2">
                                <div class="add_form col-md-5 mb-2">
                                    <label class="form-label fw-bold">Day Title <span class="text-danger">*</span></label>
                                    <input type="text" name="tour_planning[0][title]"
                                        class="form-control py-2 rounded-3 shadow-sm"
                                        placeholder="Day Title (e.g., Day 1)">
                                </div>
                                <div class="add_form col-md-5 mb-2">
                                    <label class="form-label fw-bold">Day Subtitle <span class="text-danger">*</span></label>
                                    <input type="text" name="tour_planning[0][subtitle]"
                                        class="form-control py-2 rounded-3 shadow-sm"
                                        placeholder="SubTitle">
                                </div>
                                <div class="add_form col-md-10 mb-2">
                                    <label class="form-label fw-bold">Activity Description <span class="text-danger">*</span></label>
                                    <div class="rte-container">
                                        <div class="editor-toolbar">
                                            <button type="button" class="toolbar-btn" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>
                                            <button type="button" class="toolbar-btn" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>
                                            <button type="button" class="toolbar-btn" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>
                                            <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                                            <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                                            <button type="button" class="toolbar-btn" data-command="createLink" title="Insert Link"><i class="fas fa-link"></i></button>
                                        </div>
                                        <div class="editor-content" contenteditable="true" id="editor-0"></div>
                                        <input type="hidden" name="tour_planning[0][description]" class="tour-description-hidden">
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <!-- Remove button, hidden for the first row -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-add rounded border-0 px-4 py-2 text-white mt-2"
                        onclick="addDay()">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add More
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let index = 1;
        const editors = {}; // Store editor instances

        function initializeRTE(editorId, hiddenInputId) {
            const editor = document.getElementById(editorId);
            const hiddenInput = document.querySelector(`input[name="${hiddenInputId}"]`);
            
            // Set up toolbar functionality
            const toolbar = editor.previousElementSibling;
            toolbar.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON' || e.target.parentElement.tagName === 'BUTTON') {
                    e.preventDefault();
                    const button = e.target.tagName === 'BUTTON' ? e.target : e.target.parentElement;
                    const command = button.dataset.command;
                    
                    if (command) {
                        let value = null;
                        
                        if (command === 'createLink') {
                            value = prompt('Enter URL:', 'https://');
                            if (value === null) return; // User cancelled
                        }
                        
                        document.execCommand(command, false, value);
                        editor.focus();
                        
                        // Update the hidden input
                        hiddenInput.value = editor.innerHTML;
                    }
                }
            });
            
            // Update hidden input when content changes
            editor.addEventListener('input', function() {
                hiddenInput.value = editor.innerHTML;
            });
            
            editor.addEventListener('blur', function() {
                hiddenInput.value = editor.innerHTML;
            });
            
            // Store editor reference
            editors[editorId] = editor;
        }

        function addDay() {
            const wrapper = document.getElementById('day-wrapper');
            const div = document.createElement('div');
            div.classList.add('day-block');
            div.innerHTML = `
                <div class="row g-2 mb-2">
                    <div class="col-md-5 mb-2">
                        <input type="text" name="tour_planning[${index}][title]" class="form-control py-2 rounded-3 shadow-sm" placeholder="Day Title (e.g., Day ${index + 1})">
                    </div>
                    <div class="col-md-5 mb-2">
                        <input type="text" name="tour_planning[${index}][subtitle]" class="form-control py-2 rounded-3 shadow-sm" placeholder="Activity Subtitle">
                    </div>
                    <div class="col-md-10 mb-2">
                        <div class="rte-container">
                            <div class="editor-toolbar">
                                <button type="button" class="toolbar-btn" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>
                                <button type="button" class="toolbar-btn" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>
                                <button type="button" class="toolbar-btn" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>
                                <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                                <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                                <button type="button" class="toolbar-btn" data-command="createLink" title="Insert Link"><i class="fas fa-link"></i></button>
                            </div>
                            <div class="editor-content" contenteditable="true" id="editor-${index}"></div>
                            <input type="hidden" name="tour_planning[${index}][description]" class="tour-description-hidden">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-day" onclick="removeDay(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            wrapper.appendChild(div);
            
            // Initialize the new editor
            setTimeout(() => {
                initializeRTE(`editor-${index}`, `tour_planning[${index}][description]`);
            }, 100);
            
            index++;
        }

        function removeDay(btn) {
            const dayBlock = btn.closest('.day-block');
            const editorId = dayBlock.querySelector('.editor-content').id;
            
            // Remove the editor instance from our tracking
            if (editors[editorId]) {
                delete editors[editorId];
            }
            
            dayBlock.remove();
        }

        // Initialize the first editor on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                initializeRTE('editor-0', 'tour_planning[0][description]');
            }, 100);
        });
    </script>
</body>
</html>