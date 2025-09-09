<!-- resources/views/ids/waltergates-card.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Waltergates Ghana Limited • Staff ID</title>
<style>
  /* ========= Base + Variables ========= */
  :root{
    --brand:#0f4c81;         /* Waltergates deep blue */
    --brand-ink:#0b2f4a;     /* darker for text accents */
    --ink:#111827;           /* default text */
    --muted:#6b7280;         /* secondary text */
    --bg:#ffffff;            /* card background */
    --radius:14px;
    --card-w:54mm;
    --card-h:86mm;
    --safe:4mm;              /* safe margin */
  }
  *{box-sizing:border-box}
  html,body{height:100%; margin:0; background:#f5f6f8; color:var(--ink); font-family:Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;}
  .sheet{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:18mm;
    justify-content:center;
    padding:18mm;
  }
  .id-card{
    width:var(--card-w);
    height:var(--card-h);
    border-radius:var(--radius);
    background:var(--bg);
    box-shadow:0 6px 24px rgba(16,24,40,.12);
    position:relative;
    overflow:hidden;
  }

  /* ======= Shared UI bits ======= */
  .strap{
    position:absolute; inset:0 0 auto 0;
    height:22mm; background:var(--brand);
    color:#fff; padding:6mm var(--safe) 3mm var(--safe);
  }
  .brand-row{
    display:flex; align-items:center; gap:6mm;
  }
  .logo{
    width:14mm; height:14mm; border-radius:10px;
    background:#fff; color:var(--brand);
    display:grid; place-items:center;
    font-weight:800; letter-spacing:0.5px;
  }
  .brand-text{
    line-height:1.1;
  }
  .brand-name{ font-weight:800; font-size:3.3mm; letter-spacing:.2px; }
  .brand-sub{ font-weight:600; font-size:2.6mm; opacity:.95 }
  .strap .label{
    margin-top:3mm; font-weight:800; letter-spacing:1.2px; font-size:2.8mm;
  }

  .body{
    position:absolute; inset:22mm 0 0 0;
    padding:0 var(--safe) var(--safe) var(--safe);
    display:flex; flex-direction:column; gap:3.2mm;
  }

  /* ======= FRONT ======= */
  .avatar{
    width:100%; aspect-ratio: 1 / 0.78;   /* wide-ish portrait window */
    border-radius:10px; overflow:hidden;
    background:#eef2f7;
    display:grid; place-items:center;
  }
  .avatar img{ width:100%; height:100%; object-fit:cover; display:block; }

  .name{ font-size:5.2mm; font-weight:900; margin-top:1mm; }
  .role{ font-size:3.2mm; color:var(--muted); margin-top:-1mm; }
  .pair{
    display:grid; grid-template-columns: auto 1fr; column-gap:3mm; row-gap:1.6mm;
    font-size:3.1mm; align-items:center;
  }
  .k{ color:var(--muted); }
  .v{ font-weight:700; letter-spacing:.2px; color:var(--brand-ink); }

  .barcode, .qrcode{
    width:100%; height:14mm; border-radius:8px;
    background:#f3f4f6; display:grid; place-items:center;
    font-size:2.6mm; color:#6b7280; border:1px dashed #e5e7eb;
  }

  .footer-note{
    text-align:center; font-size:2.6mm; color:var(--muted);
  }

  /* ======= BACK ======= */
  .strip{
    width:100%; height:14mm; background:#1f2937; margin:22mm 0 4mm 0;
  }
  .info{
    display:grid; gap:2.8mm; font-size:3mm;
  }
  .info .row{
    display:grid; grid-template-columns: 18mm 1fr; column-gap:3mm;
  }
  .policy{
    margin-top:2mm; font-size:2.8mm; color:#374151;
    background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;
    padding:3mm;
  }
  .sign-block{
    margin-top:auto; display:grid; gap:2mm; justify-items:center;
  }
  .sign-line{
    width:70%; height:0; border-top:1px dashed #9ca3af;
  }
  .sign-caption{ font-size:2.7mm; color:#6b7280 }
  .back .codes{
    display:grid; grid-template-columns: 1fr 1fr; gap:3mm; margin-top:3mm;
  }

  /* ======= Lanyard slot mock ======= */
  .slot{
    position:absolute; inset:6mm auto auto 50%; transform:translateX(-50%);
    width:18mm; height:4mm; background:#e5e7eb; border-radius:2mm;
    box-shadow: inset 0 1px 0 rgba(0,0,0,.08);
  }

  /* ======= Print layout ======= */
  @page{ size:A4; margin:0 }
  @media print{
    body{ background:#fff; }
    .sheet{ gap:12mm; padding:12mm; }
    .id-card{ box-shadow:none; }
  }
</style>