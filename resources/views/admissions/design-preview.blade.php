@extends('admissions.layouts.header')
@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-palette"></i> Design System Preview</h1>
        <p>Visual guide to the new UI/UX design system</p>
    </div>

    <!-- Color Palette -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-paint-palette"></i> Color Palette</h3>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div style="background: #1e40af; height: 80px; border-radius: 10px 10px 0 0;"></div>
                <div class="card-body p-3">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Primary</p>
                    <code style="font-size: 11px;">#1e40af</code>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div style="background: #10b981; height: 80px; border-radius: 10px 10px 0 0;"></div>
                <div class="card-body p-3">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Success</p>
                    <code style="font-size: 11px;">#10b981</code>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div style="background: #ef4444; height: 80px; border-radius: 10px 10px 0 0;"></div>
                <div class="card-body p-3">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Danger</p>
                    <code style="font-size: 11px;">#ef4444</code>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div style="background: #f59e0b; height: 80px; border-radius: 10px 10px 0 0;"></div>
                <div class="card-body p-3">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Warning</p>
                    <code style="font-size: 11px;">#f59e0b</code>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div style="background: #0f172a; height: 80px; border-radius: 10px 10px 0 0;"></div>
                <div class="card-body p-3">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Dark</p>
                    <code style="font-size: 11px;">#0f172a</code>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-mouse"></i> Buttons</h3>
        </div>
        <div class="col-12">
            <button class="btn btn-primary me-2 mb-2"><i class="fas fa-check"></i> Primary</button>
            <button class="btn btn-success me-2 mb-2"><i class="fas fa-check"></i> Success</button>
            <button class="btn btn-danger me-2 mb-2"><i class="fas fa-trash"></i> Danger</button>
            <button class="btn btn-warning me-2 mb-2"><i class="fas fa-exclamation"></i> Warning</button>
            <button class="btn btn-secondary mb-2"><i class="fas fa-cog"></i> Secondary</button>
        </div>
    </div>

    <!-- Badges -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-tag"></i> Badges</h3>
        </div>
        <div class="col-12">
            <span class="badge bg-primary me-2 mb-2">Primary</span>
            <span class="badge bg-success me-2 mb-2">Success</span>
            <span class="badge bg-danger me-2 mb-2">Danger</span>
            <span class="badge bg-warning text-dark me-2 mb-2">Warning</span>
            <span class="badge bg-info me-2 mb-2">Info</span>
            <span class="badge bg-secondary mb-2">Secondary</span>
        </div>
    </div>

    <!-- Alerts -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-bell"></i> Alerts</h3>
        </div>
        <div class="col-12">
            <div class="alert alert-success mb-2" role="alert">
                <i class="fas fa-check-circle"></i> This is a success alert with an example.
            </div>
            <div class="alert alert-info mb-2" role="alert">
                <i class="fas fa-info-circle"></i> This is an info alert with an example.
            </div>
            <div class="alert alert-warning mb-2" role="alert">
                <i class="fas fa-exclamation-triangle"></i> This is a warning alert with an example.
            </div>
            <div class="alert alert-danger mb-2" role="alert">
                <i class="fas fa-exclamation-circle"></i> This is a danger alert with an example.
            </div>
        </div>
    </div>

    <!-- Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-credit-card"></i> Cards</h3>
        </div>
        <div class="col-md-4">
            <div class="card hover-lift">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-graduation-cap"></i> Students</h5>
                    <p class="card-text">Manage student admissions and profiles.</p>
                    <a href="#" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card hover-lift">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-files"></i> Applications</h5>
                    <p class="card-text">Track and review admission applications.</p>
                    <a href="#" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card hover-lift">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cog"></i> Settings</h5>
                    <p class="card-text">Configure application settings.</p>
                    <a href="#" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-keyboard"></i> Form Elements</h3>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="inputEmail" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="inputSelect" class="form-label">Select an option</label>
                        <select class="form-select" id="inputSelect">
                            <option selected>Choose an option</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputText" class="form-label">Text input</label>
                        <input type="text" class="form-control" id="inputText" placeholder="Enter text">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-chart-line"></i> Progress Bars</h3>
        </div>
        <div class="col-12">
            <p class="mb-2">Step 1: 20%</p>
            <div class="progress mb-4">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">20%</div>
            </div>
            
            <p class="mb-2">Step 2: 40%</p>
            <div class="progress mb-4">
                <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40%</div>
            </div>
            
            <p class="mb-2">Step 3: 60%</p>
            <div class="progress mb-4">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
            </div>
            
            <p class="mb-2">Step 4: 80%</p>
            <div class="progress mb-4">
                <div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
            </div>
            
            <p class="mb-2">Step 5: 100%</p>
            <div class="progress mb-4">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
            </div>
        </div>
    </div>

    <!-- Typography -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4"><i class="fas fa-font"></i> Typography</h3>
        </div>
        <div class="col-12">
            <h1>Heading 1</h1>
            <h2>Heading 2</h2>
            <h3>Heading 3</h3>
            <h4>Heading 4</h4>
            <h5>Heading 5</h5>
            <h6>Heading 6</h6>
            <p class="lead">This is a lead paragraph. It stands out from regular text.</p>
            <p>This is regular paragraph text that describes something important.</p>
            <p class="text-muted">This is muted text for secondary information.</p>
        </div>
    </div>
@endsection
