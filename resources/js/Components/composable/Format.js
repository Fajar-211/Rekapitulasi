export function formatuanginput(harga) {
    const angka = String(harga).replace(/[^0-9,]/g, '');
    if (!angka) return ''
    return Number(angka).toLocaleString('id-ID')
}
export function formattonaseinput(value) {
    const angka = String(value).replace(/[^0-9,]/g, '');
    if (!angka) return ''
    return angka;
}
export function clear(number) {
    if (!number) return null
    return Number(
        String(number)
            .replace(/\./g, '')  // hapus titik ribuan
            .replace(',', '.')   // ubah koma jadi titik
    )
}
export function tonaseview(value) {
    if (value === null || value === undefined) return ''
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value)
}