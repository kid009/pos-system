@extends('layouts.app')

@section('title', 'Log Activities')

@push('css')
    <style>
        .json-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            font-family: monospace;
        }

        .text-old {
            color: #dc3545;
            text-decoration: line-through;
        }

        /* สีแดงขีดฆ่า */
        .text-new {
            color: #198754;
            font-weight: bold;
        }

        /* สีเขียวตัวหนา */
    </style>
@endpush

@section('content')
    <div class="container-fluid bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">🔍 ประวัติการใช้งานระบบ (System Logs)</h3>

        <form action="{{ route('system-logs.index') }}" method="GET" class="mb-4 bg-white p-3 rounded border shadow-sm">
            <h6 class="text-muted fw-bold mb-3"><span data-feather="filter"></span> ตัวกรองการค้นหา</h6>
            <div class="row g-2">
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">ตั้งแต่วันที่</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ $startDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">ถึงวันที่</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">ผู้ทำรายการ (ชื่อ)</label>
                    <input type="text" name="causer" class="form-control form-control-sm" placeholder="ค้นหาชื่อ..."
                        value="{{ $causer }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">โมดูล (ตาราง)</label>
                    <select name="log_name" class="form-select form-select-sm">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach ($logNames as $name)
                            <option value="{{ $name }}" {{ $logName === $name ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">เหตุการณ์</label>
                    <select name="description" class="form-select form-select-sm">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event }}" {{ $description === $event ? 'selected' : '' }}>
                                @if ($event === 'created')
                                    สร้างใหม่
                                @elseif($event === 'updated')
                                    แก้ไข
                                @elseif($event === 'deleted')
                                    ลบ
                                @else
                                    {{ $event }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100">ค้นหา</button>
                </div>
            </div>

            @if ($startDate || $endDate || $causer || $logName || $description)
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('system-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                            ❌ ล้างตัวกรองทั้งหมด
                        </a>
                    </div>
                </div>
            @endif
        </form>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th width="150">วัน-เวลา</th>
                    <th width="150">ผู้ทำรายการ</th>
                    <th width="150">โมดูล (ตาราง)</th>
                    <th width="100">เหตุการณ์</th>
                    <th>รายละเอียดการเปลี่ยนแปลง (Changes)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $log->causer ? $log->causer->name : 'ระบบอัตโนมัติ (System)' }}
                            </span>
                        </td>

                        <td>{{ $log->log_name }}</td>

                        <td>
                            @if ($log->description === 'created')
                                <span class="badge bg-success">สร้างใหม่</span>
                            @elseif($log->description === 'updated')
                                <span class="badge bg-warning text-dark">แก้ไข</span>
                            @elseif($log->description === 'deleted')
                                <span class="badge bg-danger">ลบ</span>
                            @else
                                <span class="badge bg-secondary">{{ $log->description }}</span>
                            @endif
                        </td>

                        <td>
                            @if ($log->description === 'updated' && isset($log->properties['old']) && isset($log->properties['attributes']))
                                <div class="json-box">
                                    <table class="table table-sm table-borderless mb-0">
                                        @foreach ($log->properties['attributes'] as $key => $newValue)
                                            @php
                                                $oldValue = $log->properties['old'][$key] ?? null;
                                            @endphp
                                            <tr>
                                                <td width="120" class="text-muted fw-bold">{{ $key }}:</td>
                                                <td>
                                                    <span class="text-old">{{ $oldValue ?? 'ว่าง' }}</span>
                                                    ➡️
                                                    <span class="text-new">{{ $newValue ?? 'ว่าง' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @elseif($log->description === 'created' && isset($log->properties['attributes']))
                                <div class="json-box text-muted">
                                    ข้อมูลเริ่มต้น:
                                    {{ json_encode($log->properties['attributes'], JSON_UNESCAPED_UNICODE) }}
                                </div>
                            @else
                                <span class="text-muted">- ไม่มีรายละเอียด -</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">ยังไม่มีประวัติการใช้งาน</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
