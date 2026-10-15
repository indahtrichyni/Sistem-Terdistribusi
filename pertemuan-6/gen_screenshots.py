#!/usr/bin/env python3
"""Generate SVG mockups illustrating the three required API test cases.
These are explanatory mockups, not captures from a live browser/terminal.
"""
from pathlib import Path

OUT = Path(__file__).parent / "docs" / "screenshots"
OUT.mkdir(parents=True, exist_ok=True)

def esc(s):
    return s.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')

def make(filename, title, method, endpoint, status, request, response, accent):
    lines = []
    y = 300
    for line in response.splitlines():
        lines.append(f'<text x="64" y="{y}" class="code">{esc(line)}</text>')
        y += 28
    req = []
    y2 = 218
    for line in request.splitlines():
        req.append(f'<text x="64" y="{y2}" class="code small">{esc(line)}</text>')
        y2 += 23
    svg = f'''<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="720" viewBox="0 0 1200 720">
<rect width="1200" height="720" fill="#f3f5f9"/><rect x="28" y="28" width="1144" height="664" rx="18" fill="#ffffff" stroke="#d6dce7"/>
<rect x="28" y="28" width="1144" height="62" rx="18" fill="#202938"/><rect x="28" y="70" width="1144" height="20" fill="#202938"/>
<circle cx="58" cy="59" r="7" fill="#f87171"/><circle cx="82" cy="59" r="7" fill="#fbbf24"/><circle cx="106" cy="59" r="7" fill="#34d399"/>
<text x="140" y="66" font-family="Arial,sans-serif" font-size="22" font-weight="bold" fill="#ffffff">Mini-LMS API Test • Pertemuan 6</text>
<text x="64" y="132" font-family="Arial,sans-serif" font-size="28" font-weight="bold" fill="#172033">{esc(title)}</text>
<rect x="64" y="151" width="100" height="38" rx="8" fill="#e0e7ff"/><text x="114" y="176" text-anchor="middle" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="#3730a3">{esc(method)}</text>
<text x="184" y="176" font-family="monospace" font-size="19" fill="#172033">{esc(endpoint)}</text>
<rect x="970" y="151" width="166" height="38" rx="8" fill="{accent}"/><text x="1053" y="176" text-anchor="middle" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="#ffffff">HTTP {status}</text>
<text x="64" y="207" font-family="Arial,sans-serif" font-size="16" font-weight="bold" fill="#64748b">REQUEST BODY</text>
<rect x="52" y="220" width="1096" height="{max(45, len(request.splitlines())*23+22)}" rx="10" fill="#f8fafc" stroke="#e2e8f0"/>{''.join(req)}
<text x="64" y="{max(285, y2+8)}" font-family="Arial,sans-serif" font-size="16" font-weight="bold" fill="#64748b">RESPONSE BODY</text>
<rect x="52" y="{max(298, y2+20)}" width="1096" height="{min(720-max(298,y2+20)-28, max(110,len(response.splitlines())*28+30))}" rx="10" fill="#111827"/>
{''.join(lines)}
<text x="64" y="666" font-family="Arial,sans-serif" font-size="14" fill="#64748b">Ilustrasi respons yang diharapkan — bukan tangkapan layar dari server langsung.</text></svg>'''
    (OUT / filename).write_text(svg, encoding='utf-8')

make('01-permintaan-valid.svg', '01 • Permintaan valid', 'POST', '/api/submissions', '201 Created',
'''{
  "assignment_id": 501,
  "student_id": 101,
  "filename": "tugas-mini-lms.zip",
  "file_size": 248000,
  "content_type": "application/zip"
}''',
'''{
  "data": {
    "submission_id": 9001,
    "assignment_id": 501,
    "student_id": 101,
    "file_id": "f-a1b2c3d4e5f6",
    "filename": "tugas-mini-lms.zip",
    "file_size": 248000,
    "content_type": "application/zip",
    "internal_location": "storage/f-a1b2c3d4e5f6.blob",
    "status": "submitted"
  }
}''', '#15803d')
make('02-assignment-tidak-ditemukan.svg', '02 • Assignment tidak ditemukan', 'POST', '/api/submissions', '404 Not Found',
'''{
  "assignment_id": 99999,
  "student_id": 101,
  "filename": "tugas.zip",
  "file_size": 1000,
  "content_type": "application/zip"
}''',
'''{
  "error": {
    "code": "assignment_not_found",
    "message": "Assignment tidak ditemukan atau tidak aktif.",
    "assignment_id": 99999
  }
}''', '#b91c1c')
make('03-input-tidak-lengkap.svg', '03 • Input tidak lengkap', 'POST', '/api/submissions', '400 Bad Request',
'''{
  "assignment_id": 501,
  "filename": "tugas.zip",
  "file_size": 1000,
  "content_type": "application/zip"
}''',
'''{
  "error": {
    "code": "input_incomplete",
    "message": "Field wajib belum lengkap.",
    "fields": ["student_id"]
  }
}''', '#b45309')
print(f"SVG ilustrasi dibuat di {OUT}")
