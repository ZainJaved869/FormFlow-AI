@extends('layouts.app')

@section('title', isset($form) ? 'Edit Form' : 'Create New Form')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
    <style>
        /* ─── Form Builder Layout ─── */
        .builder-wrapper {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }
        .builder-palette {
            flex: 0 0 220px;
            position: sticky;
            top: 20px;
        }
        .builder-canvas {
            flex: 1;
            min-height: 600px;
        }
        .builder-properties {
            flex: 0 0 280px;
            position: sticky;
            top: 20px;
        }

        /* ─── Palette Items ─── */
        .palette-item {
            padding: 10px 14px;
            margin-bottom: 6px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            cursor: grab;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: var(--bs-body-color);
        }
        .palette-item:hover {
            border-color: var(--primary);
            background: rgba(108,99,255,0.06);
            transform: translateX(4px);
        }
        .palette-item i {
            font-size: 1.1rem;
            color: var(--primary);
            width: 20px;
        }
        .palette-item.dragging {
            opacity: 0.4;
        }

        /* ─── Canvas Drop Zone ─── */
        .drop-zone {
            min-height: 400px;
            border: 2px dashed var(--glass-border);
            border-radius: 20px;
            padding: 20px;
            background: var(--glass-bg);
            transition: all 0.3s;
            position: relative;
        }
        .drop-zone.drag-over {
            border-color: var(--primary);
            background: rgba(108,99,255,0.05);
            box-shadow: inset 0 0 30px rgba(108,99,255,0.03);
        }
        .drop-zone .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: var(--bs-secondary-color);
        }
        .drop-zone .empty-message i {
            font-size: 3rem;
            opacity: 0.3;
            margin-bottom: 12px;
            display: block;
        }

        /* ─── Field Cards ─── */
        .field-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 12px;
            cursor: grab;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .field-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 20px rgba(108,99,255,0.08);
            transform: translateY(-2px);
        }
        .field-card .field-label {
            font-weight: 500;
            font-size: 0.95rem;
        }
        .field-card .field-type {
            font-size: 0.75rem;
            color: var(--bs-secondary-color);
            background: rgba(108,99,255,0.08);
            padding: 2px 12px;
            border-radius: 60px;
        }
        .field-card .field-actions button {
            background: none;
            border: none;
            color: var(--bs-secondary-color);
            transition: color 0.2s;
        }
        .field-card .field-actions button:hover {
            color: #dc3545;
        }

        /* ─── Properties Panel ─── */
        .properties-panel {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 20px;
        }
        .properties-panel .property-group {
            margin-bottom: 16px;
        }
        .properties-panel label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--bs-secondary-color);
            margin-bottom: 4px;
            display: block;
        }
        .properties-panel .form-control {
            background: var(--bs-body-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.9rem;
        }
        .properties-panel .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }
        .properties-panel .form-switch .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* ─── AI Generator Modal Trigger ─── */
        .ai-trigger {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 60px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 30px rgba(108,99,255,0.25);
            width: 100%;
        }
        .ai-trigger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(108,99,255,0.35);
            color: white;
        }

        /* ─── Responsive ─── */
        @media (max-width: 992px) {
            .builder-wrapper {
                flex-direction: column;
            }
            .builder-palette,
            .builder-properties {
                flex: 1;
                position: static;
                width: 100%;
            }
            .builder-palette .d-flex {
                flex-wrap: wrap;
                gap: 6px;
            }
            .builder-palette .palette-item {
                flex: 1 0 calc(50% - 6px);
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">{{ isset($form) ? 'Edit Form' : 'Create New Form' }}</h4>
            <p class="text-muted small">Drag fields from the left or use AI to generate</p>
        </div>
        <div>
            <button type="submit" form="formBuilderForm" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-check-lg me-1"></i> {{ isset($form) ? 'Update' : 'Save' }} Form
            </button>
            @if(isset($form))
                <a href="{{ route('public.form', $form->shareable_link) }}" class="btn btn-outline-secondary rounded-pill" target="_blank">
                    <i class="bi bi-eye me-1"></i> Preview
                </a>
            @endif
        </div>
    </div>

    <!-- Builder Layout -->
    <div class="builder-wrapper">
        <!-- Left: Palette -->
        <aside class="builder-palette">
            <div class="glass p-3 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-grid-3x3-gap me-2" style="color:var(--primary);"></i>Fields</h6>
                <div id="palette">
                    @php
                        $fieldTypes = [
                            'text'      => ['icon' => 'bi-font', 'label' => 'Text Input'],
                            'textarea'  => ['icon' => 'bi-align-left', 'label' => 'Text Area'],
                            'email'     => ['icon' => 'bi-envelope', 'label' => 'Email'],
                            'phone'     => ['icon' => 'bi-telephone', 'label' => 'Phone'],
                            'dropdown'  => ['icon' => 'bi-caret-down', 'label' => 'Dropdown'],
                            'radio'     => ['icon' => 'bi-circle', 'label' => 'Radio'],
                            'checkbox'  => ['icon' => 'bi-check-square', 'label' => 'Checkbox'],
                            'file'      => ['icon' => 'bi-upload', 'label' => 'File Upload'],
                            'date'      => ['icon' => 'bi-calendar', 'label' => 'Date Picker'],
                            'rating'    => ['icon' => 'bi-star', 'label' => 'Rating'],
                            'signature' => ['icon' => 'bi-pencil', 'label' => 'Signature'],
                        ];
                    @endphp
                    @foreach($fieldTypes as $type => $info)
                        <div class="palette-item" data-type="{{ $type }}" draggable="true">
                            <i class="bi {{ $info['icon'] }}"></i>
                            <span>{{ $info['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- AI Generator -->
            <div class="glass p-3 rounded-4 mt-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-robot me-2" style="color:var(--primary);"></i>AI Generator</h6>
                <div class="input-group mb-2">
                    <input type="text" id="aiPrompt" class="form-control rounded-pill" placeholder='e.g. "Job application form"'>
                </div>
                <button type="button" class="ai-trigger" onclick="generateAI()">
                    <i class="bi bi-magic me-1"></i> Generate with AI
                </button>
            </div>
        </aside>

        <!-- Center: Canvas -->
        <section class="builder-canvas">
            <div class="drop-zone" id="dropZone">
                <div id="fieldsContainer">
                    @if(isset($form) && $form->fields->count())
                        @foreach($form->fields as $field)
                            <div class="field-card" data-field-id="{{ $field->id }}" data-index="{{ $loop->index }}">
                                <div>
                                    <span class="field-label">{{ $field->label }}</span>
                                    <span class="field-type">{{ ucfirst($field->type) }}</span>
                                </div>
                                <div class="field-actions">
                                    <button type="button" class="btn-remove-field" data-id="{{ $field->id }}" title="Remove field">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="fields[{{ $loop->index }}][type]" value="{{ $field->type }}">
                                <input type="hidden" name="fields[{{ $loop->index }}][label]" value="{{ $field->label }}">
                                <input type="hidden" name="fields[{{ $loop->index }}][placeholder]" value="{{ $field->placeholder }}">
                                <input type="hidden" name="fields[{{ $loop->index }}][options]" value="{{ json_encode($field->options) }}">
                                <input type="hidden" name="fields[{{ $loop->index }}][required]" value="{{ $field->required ? 1 : 0 }}">
                                <input type="hidden" name="fields[{{ $loop->index }}][order]" value="{{ $field->order }}">
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="empty-message" id="emptyMessage" style="{{ isset($form) && $form->fields->count() ? 'display:none;' : '' }}">
                    <i class="bi bi-arrow-down"></i>
                    <p>Drag fields here or use AI to generate</p>
                </div>
            </div>
        </section>

        <!-- Right: Properties -->
        <aside class="builder-properties">
            <div class="properties-panel">
                <h6 class="fw-bold mb-3"><i class="bi bi-sliders me-2" style="color:var(--primary);"></i>Properties</h6>
                <div class="property-group">
                    <label>Form Title</label>
                    <input type="text" id="formTitleInput" class="form-control" value="{{ old('title', $form->title ?? 'Untitled Form') }}" placeholder="Enter title">
                </div>
                <div class="property-group">
                    <label>Description</label>
                    <textarea id="formDescInput" class="form-control" rows="2" placeholder="Describe your form">{{ old('description', $form->description ?? '') }}</textarea>
                </div>
                <div class="property-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="publishedSwitch" name="is_published" value="1" {{ old('is_published', $form->is_published ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="publishedSwitch">Published</label>
                    </div>
                </div>
                <hr>
                <div class="property-group">
                    <label>Selected Field</label>
                    <div id="selectedFieldInfo" class="text-muted small text-center py-2">Click a field to edit</div>
                    <div id="fieldProperties" style="display:none;">
                        <div class="mb-2">
                            <label>Label</label>
                            <input type="text" id="fieldLabelInput" class="form-control" placeholder="Label">
                        </div>
                        <div class="mb-2">
                            <label>Placeholder</label>
                            <input type="text" id="fieldPlaceholderInput" class="form-control" placeholder="Placeholder">
                        </div>
                        <div class="mb-2">
                            <label>Options (comma separated)</label>
                            <input type="text" id="fieldOptionsInput" class="form-control" placeholder="Option1, Option2, Option3">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="fieldRequiredInput">
                            <label class="form-check-label">Required</label>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm w-100 mt-2" onclick="applyFieldChanges()">Apply Changes</button>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="showQR()">
                        <i class="bi bi-qr-code me-1"></i> Generate QR
                    </button>
                </div>
            </div>
        </aside>
    </div>

    <!-- Hidden Form for Submission -->
    <form id="formBuilderForm" method="POST" action="{{ isset($form) ? route('forms.update', $form->id) : route('forms.store') }}" style="display:none;">
        @csrf
        @if(isset($form)) @method('PUT') @endif
        <input type="hidden" name="title" id="hiddenTitle">
        <input type="hidden" name="description" id="hiddenDescription">
        <input type="hidden" name="is_published" id="hiddenPublished">
        <div id="hiddenFieldsContainer"></div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    (function() {
        'use strict';

        // ── State ──
        let fields = [];
        let selectedFieldIndex = null;
        const container = document.getElementById('fieldsContainer');
        const emptyMsg = document.getElementById('emptyMessage');
        const dropZone = document.getElementById('dropZone');

        // ── Load existing fields ──
        function loadFields() {
            const cards = container.querySelectorAll('.field-card');
            fields = [];
            cards.forEach(card => {
                const index = parseInt(card.dataset.index);
                const type = card.querySelector('input[name$="[type]"]').value;
                const label = card.querySelector('input[name$="[label]"]').value;
                const placeholder = card.querySelector('input[name$="[placeholder]"]')?.value || '';
                const options = JSON.parse(card.querySelector('input[name$="[options]"]')?.value || '[]');
                const required = card.querySelector('input[name$="[required]"]')?.value === '1';
                const order = parseInt(card.dataset.index);
                fields.push({ type, label, placeholder, options, required, order });
            });
            updateEmptyState();
        }

        // ── Update empty state ──
        function updateEmptyState() {
            emptyMsg.style.display = fields.length === 0 ? 'block' : 'none';
        }

        // ── Render all fields ──
        function renderFields() {
            container.innerHTML = fields.map((f, idx) => {
                const label = f.label || f.type.charAt(0).toUpperCase() + f.type.slice(1);
                return `
                    <div class="field-card" data-index="${idx}" data-type="${f.type}">
                        <div>
                            <span class="field-label">${label}</span>
                            <span class="field-type">${f.type}</span>
                        </div>
                        <div class="field-actions">
                            <button type="button" class="btn-remove-field" data-index="${idx}" title="Remove field">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="fields[${idx}][type]" value="${f.type}">
                        <input type="hidden" name="fields[${idx}][label]" value="${f.label}">
                        <input type="hidden" name="fields[${idx}][placeholder]" value="${f.placeholder || ''}">
                        <input type="hidden" name="fields[${idx}][options]" value='${JSON.stringify(f.options || [])}'>
                        <input type="hidden" name="fields[${idx}][required]" value="${f.required ? 1 : 0}">
                        <input type="hidden" name="fields[${idx}][order]" value="${idx}">
                    </div>
                `;
            }).join('');
            updateEmptyState();
            attachFieldEvents();
        }

        // ── Attach events to field cards ──
        function attachFieldEvents() {
            document.querySelectorAll('.field-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('.btn-remove-field')) return;
                    const idx = parseInt(this.dataset.index);
                    selectField(idx);
                });
                const removeBtn = card.querySelector('.btn-remove-field');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const idx = parseInt(this.dataset.index);
                        removeField(idx);
                    });
                }
            });
        }

        // ── Add field ──
        function addField(type, data = null) {
            const fieldMap = {
                text: { label: 'Text Input', placeholder: 'Enter text...', options: [] },
                textarea: { label: 'Text Area', placeholder: 'Enter details...', options: [] },
                email: { label: 'Email', placeholder: 'you@example.com', options: [] },
                phone: { label: 'Phone', placeholder: '+1 234 567 890', options: [] },
                dropdown: { label: 'Dropdown', placeholder: '', options: ['Option 1', 'Option 2', 'Option 3'] },
                radio: { label: 'Radio Group', placeholder: '', options: ['Option 1', 'Option 2'] },
                checkbox: { label: 'Checkbox', placeholder: '', options: [] },
                file: { label: 'File Upload', placeholder: '', options: [] },
                date: { label: 'Date Picker', placeholder: '', options: [] },
                rating: { label: 'Rating', placeholder: '', options: [] },
                signature: { label: 'Signature', placeholder: '', options: [] },
            };
            const info = data || fieldMap[type] || { label: 'New Field', placeholder: '', options: [] };
            const newField = {
                type: type,
                label: info.label,
                placeholder: info.placeholder || '',
                options: info.options || [],
                required: false,
                order: fields.length,
            };
            fields.push(newField);
            renderFields();
            // Auto-select the new field
            selectField(fields.length - 1);
        }

        // ── Remove field ──
        function removeField(index) {
            if (index === null || index === undefined) return;
            fields.splice(index, 1);
            renderFields();
            if (selectedFieldIndex === index) {
                selectedFieldIndex = null;
                clearPropertiesPanel();
            }
        }

        // ── Select field for editing ──
        function selectField(index) {
            if (index === null || index === undefined) return;
            selectedFieldIndex = index;
            const f = fields[index];
            if (!f) return;
            document.getElementById('fieldProperties').style.display = 'block';
            document.getElementById('selectedFieldInfo').style.display = 'none';
            document.getElementById('fieldLabelInput').value = f.label || '';
            document.getElementById('fieldPlaceholderInput').value = f.placeholder || '';
            document.getElementById('fieldOptionsInput').value = (f.options || []).join(', ');
            document.getElementById('fieldRequiredInput').checked = f.required || false;
            // Highlight selected card
            document.querySelectorAll('.field-card').forEach((card, i) => {
                card.style.borderColor = (i === index) ? 'var(--primary)' : '';
                card.style.background = (i === index) ? 'rgba(108,99,255,0.04)' : '';
            });
        }

        // ── Clear properties panel ──
        function clearPropertiesPanel() {
            document.getElementById('fieldProperties').style.display = 'none';
            document.getElementById('selectedFieldInfo').style.display = 'block';
            document.querySelectorAll('.field-card').forEach(card => {
                card.style.borderColor = '';
                card.style.background = '';
            });
        }

        // ── Apply changes to selected field ──
        window.applyFieldChanges = function() {
            if (selectedFieldIndex === null || selectedFieldIndex === undefined) {
                alert('No field selected');
                return;
            }
            const f = fields[selectedFieldIndex];
            f.label = document.getElementById('fieldLabelInput').value.trim() || f.label;
            f.placeholder = document.getElementById('fieldPlaceholderInput').value.trim();
            const optionsStr = document.getElementById('fieldOptionsInput').value.trim();
            f.options = optionsStr ? optionsStr.split(',').map(s => s.trim()).filter(s => s) : [];
            f.required = document.getElementById('fieldRequiredInput').checked;
            renderFields();
            selectField(selectedFieldIndex); // re-select to show updated values
        };

        // ── AI Generation ──
        window.generateAI = function() {
            const prompt = document.getElementById('aiPrompt').value.trim();
            if (!prompt) {
                alert('Please enter a prompt.');
                return;
            }
            fetch('{{ route("forms.ai-generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt })
            })
            .then(res => res.json())
            .then(data => {
                if (data.fields && data.fields.length) {
                    // Replace all fields with AI-generated ones
                    fields = data.fields.map(f => ({
                        type: f.type,
                        label: f.label,
                        placeholder: f.placeholder || '',
                        options: f.options || [],
                        required: f.required || false,
                        order: 0,
                    }));
                    renderFields();
                    selectedFieldIndex = null;
                    clearPropertiesPanel();
                    alert('AI generated ' + fields.length + ' fields!');
                } else {
                    alert('AI generation failed. Please try again.');
                }
            })
            .catch(err => {
                alert('Error: ' + err.message);
            });
        };

        // ── Form submission ──
        document.getElementById('formBuilderForm').addEventListener('submit', function(e) {
            const title = document.getElementById('formTitleInput').value.trim();
            if (!title) {
                e.preventDefault();
                alert('Please enter a form title.');
                return;
            }
            document.getElementById('hiddenTitle').value = title;
            document.getElementById('hiddenDescription').value = document.getElementById('formDescInput').value;
            document.getElementById('hiddenPublished').value = document.getElementById('publishedSwitch').checked ? 1 : 0;
            // Rebuild hidden fields container
            const hiddenContainer = document.getElementById('hiddenFieldsContainer');
            hiddenContainer.innerHTML = '';
            fields.forEach((f, idx) => {
                const html = `
                    <input type="hidden" name="fields[${idx}][type]" value="${f.type}">
                    <input type="hidden" name="fields[${idx}][label]" value="${f.label}">
                    <input type="hidden" name="fields[${idx}][placeholder]" value="${f.placeholder || ''}">
                    <input type="hidden" name="fields[${idx}][options]" value='${JSON.stringify(f.options || [])}'>
                    <input type="hidden" name="fields[${idx}][required]" value="${f.required ? 1 : 0}">
                    <input type="hidden" name="fields[${idx}][order]" value="${idx}">
                `;
                hiddenContainer.innerHTML += html;
            });
        });

        // ── Drag & Drop (Palette) ──
        document.querySelectorAll('.palette-item').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.dataset.type);
                this.classList.add('dragging');
            });
            item.addEventListener('dragend', function(e) {
                this.classList.remove('dragging');
            });
        });

        // ── Drop Zone ──
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', function(e) {
            this.classList.remove('drag-over');
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            const type = e.dataTransfer.getData('text/plain');
            if (type) {
                addField(type);
            }
        });

        // ── Sortable (reorder) ──
        Sortable.create(container, {
            animation: 150,
            onEnd: function() {
                // Update order in fields array
                const cards = container.querySelectorAll('.field-card');
                const newOrder = [];
                cards.forEach((card, idx) => {
                    const oldIdx = parseInt(card.dataset.index);
                    newOrder.push(fields[oldIdx]);
                });
                fields = newOrder;
                renderFields();
                // Re-select if needed
                if (selectedFieldIndex !== null) {
                    selectField(selectedFieldIndex);
                }
            }
        });

        // ── Init ──
        loadFields();
        if (fields.length === 0) {
            clearPropertiesPanel();
        } else {
            selectField(0);
        }

        // ── QR placeholder ──
        window.showQR = function() {
            alert('QR Code generation will be available after saving the form.');
        };

    })();
</script>
@endpush
@endsection