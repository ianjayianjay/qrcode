let lastScannedCode = "";
let lastScanTime = 0;

function onScanSuccess(decodedText) {
  const now = Date.now();
  if (decodedText === lastScannedCode && now - lastScanTime < 60000) {
    console.log("Ignored repeated scan within 60 seconds.");
    return; // Ignore duplicate scan
  }

  lastScannedCode = decodedText;
  lastScanTime = now;

  fetch("scan_action.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ code: decodedText })
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById("scanResult").innerText = data.message;
    loadStudents();
  });
}

const scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
scanner.render(onScanSuccess);