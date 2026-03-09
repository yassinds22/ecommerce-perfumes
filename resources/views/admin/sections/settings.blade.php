<!-- ===== SETTINGS SECTION ===== -->
<section class="dashboard-section {{ ($isActive ?? false) ? 'active' : '' }}" id="settings">
    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
        @csrf
        <div class="settings-tabs">
            <button type="button" class="settings-tab-btn active" data-group="general">العامة</button>
            <button type="button" class="settings-tab-btn" data-group="contact">التواصل</button>
            <button type="button" class="settings-tab-btn" data-group="social">التواصل الاجتماعي</button>
            <button type="button" class="settings-tab-btn" data-group="seo">SEO</button>
        </div>

        <div class="settings-content">
            @foreach($settings as $group => $items)
            <div class="settings-group {{ $loop->first ? 'active' : '' }}" id="group-{{ $group }}">
                <div class="table-card">
                    <div class="table-card__header">
                        <h3>{{ ucfirst($group) }} الإعدادات</h3>
                    </div>
                    <div style="padding: 24px">
                        @foreach($items as $setting)
                        <div class="form-group" style="margin-bottom: 20px">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem">
                                {{ $setting->display_name }}
                            </label>
                            
                            @if($setting->type === 'textarea')
                                <textarea name="{{ $setting->key }}" class="form-control" style="width: 100%; min-height: 100px; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; color: var(--color-text); padding: 12px; font-family: inherit">{{ $setting->value }}</textarea>
                            @elseif($setting->type === 'image')
                                <div style="display: flex; gap: 16px; align-items: center">
                                    <div style="width: 80px; height: 80px; background: var(--bg-input); border-radius: 8px; border: 1px dashed var(--color-gold); display: flex; align-items: center; justify-content: center">
                                        @if($setting->value)
                                            <img src="{{ $setting->value }}" style="max-width: 100%; max-height: 100%; object-fit: contain">
                                        @else
                                            <i class="fas fa-image" style="color: var(--color-gold)"></i>
                                        @endif
                                    </div>
                                    <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" style="flex: 1; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; color: var(--color-text); padding: 10px 16px" placeholder="رابط الصورة او المسار">
                                </div>
                            @else
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" style="width: 100%; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; color: var(--color-text); padding: 10px 16px">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end">
            <button type="submit" class="btn btn-gold" style="padding: 12px 32px">
                <i class="fas fa-save" style="margin-left: 8px"></i> حفظ التغييرات
            </button>
        </div>
    </form>

    <style>
        .settings-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }
        .settings-tab-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--color-text-muted);
            transition: all 0.3s ease;
        }
        .settings-tab-btn:hover {
            background: rgba(201, 168, 76, 0.1);
            color: var(--color-gold);
        }
        .settings-tab-btn.active {
            background: var(--color-gold);
            color: #000;
        }
        .settings-group {
            display: none;
        }
        .settings-group.active {
            display: block;
        }
    </style>

    <script>
        document.querySelectorAll('.settings-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.settings-group').forEach(g => g.classList.remove('active'));
                
                btn.classList.add('active');
                document.getElementById('group-' + btn.dataset.group).classList.add('active');
            });
        });

        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            if (window.fetch) {
                e.preventDefault();
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
                btn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('حدث خطأ أثناء الحفظ', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.getElementById('dashToast');
            if (!toast) return;
            
            toast.textContent = message;
            toast.className = 'toast show ' + type;
            
            setTimeout(() => {
                toast.className = 'toast';
            }, 3000);
        }
    </script>
</section>
