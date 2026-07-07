//generar qr
document.querySelectorAll('.qrcode').forEach((canvas) => {
    const datos = canvas.dataset.qr;
    QRCode.toCanvas(canvas, datos, {
        width: 185,
        margin: 1
    }, function(error){
        if(error){
            console.error(error);
        }
    });
});