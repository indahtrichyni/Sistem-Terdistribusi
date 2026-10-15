#!/usr/bin/env bash
set -euo pipefail
BASE_URL="${BASE_URL:-http://127.0.0.1:8000}"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

request() {
  local name="$1" expected="$2" method="$3" path="$4" body="${5:-}"
  if [[ "$method" == "POST" ]]; then
    code=$(curl -sS -o "$TMP/body.json" -w '%{http_code}' -X POST "$BASE_URL$path" -H 'Content-Type: application/json' -d "$body")
  else
    code=$(curl -sS -o "$TMP/body.json" -w '%{http_code}' "$BASE_URL$path")
  fi
  echo "[$name] HTTP $code (expected $expected)"
  cat "$TMP/body.json"; echo
  if [[ "$code" != "$expected" ]]; then echo "GAGAL: status tidak sesuai" >&2; exit 1; fi
  cp "$TMP/body.json" "$TMP/$name.json"
}

request valid 201 POST /api/submissions '{"assignment_id":501,"student_id":101,"filename":"tugas-mini-lms.zip","file_size":248000,"content_type":"application/zip"}'
ID="$(php -r '$j=json_decode(file_get_contents($argv[1]),true); echo $j["data"]["submission_id"] ?? "";' "$TMP/valid.json")"
FILE_ID="$(php -r '$j=json_decode(file_get_contents($argv[1]),true); echo $j["data"]["file_id"] ?? "";' "$TMP/valid.json")"
[[ -n "$ID" && -n "$FILE_ID" ]] || { echo "Respons valid tidak memuat identifier" >&2; exit 1; }

request assignment-tidak-ditemukan 404 POST /api/submissions '{"assignment_id":99999,"student_id":101,"filename":"tugas.zip","file_size":1000,"content_type":"application/zip"}'
request input-tidak-lengkap 400 POST /api/submissions '{"assignment_id":501,"filename":"tugas.zip","file_size":1000,"content_type":"application/zip"}'
request get-submission 200 GET "/api/submissions/$ID"
request get-file 200 GET "/api/files/$FILE_ID"
echo "SEMUA PENGUJIAN BERHASIL."
