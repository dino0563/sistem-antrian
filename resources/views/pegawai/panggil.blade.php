@extends('layouts.pegawai')

@section('name', 'Panggil')

@section('content')

<div id="layoutSidenav_content">
    <div class="container">
        <div class="form-group mt-1">
            <div class="form-group">
                <br>
                <select class="form-control" id="loketsSelect">
                    @foreach ($loketOptions as $loket)
                    <option value="{{ $loket->id }}">{{ $loket->nama }}</option>
                    @endforeach
                </select>
                <h6 class="mt-2" id="selectedLoketInfo">Anda berada di <span id="selectedLoketName"></span></h6>
            </div>
            <button class="btn btn-primary" onclick="selectLoket()">Pilih Loket</button>
            <div class="scroll">
                <div class="card w-80 mb-3 mt-4" id="antrianContainer">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nomor Antrian</th>
                                <th scope="col">Aksi</th>
                                <th scopr="col">Status</th>
                                <th scopr="col">Loket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ str_pad($item['nomor_antrian'], 3, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <button class="btn btn-secondary fa fa-microphone" data-id="{{ $item['id'] }}" data-loket="{{ $loket->id }}" onclick="callAntrian('{{ $item['nomor_antrian'] }}',  {{ $item['id'] }})"></button>
                                    <button class="btn btn-primary fa fa-user" data-id="{{ $item['id'] }}" onclick="UbahDilayani('{{ $item['nomor_antrian'] }}',  {{ $item['id'] }})"></button>
                                    <button class="btn btn-success fa fa-check" data-id="{{ $item['id'] }}" onclick="UbahSelesai('{{ $item['nomor_antrian'] }}',  {{ $item['id'] }})"></button>
                                    <button class="btn btn-danger fa fa-times" data-id="{{ $item['id'] }}" onclick="UbahTidakDatang('{{ $item['nomor_antrian'] }}',  {{ $item['id'] }})"></button>
                                </td>
                                <td class="status-cell">{{ $item['status'] }}</td>
                                <td class="loket-cell">{{ $item['loket_nama'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk memperbarui tampilan antrian
        function updateQueueView(data) {
            const antrianContainer = document.getElementById("antrianContainer");
            antrianContainer.innerHTML = data; // Memasukkan data HTML ke dalam container

            // Tampilkan kolom "Loket" untuk semua baris yang memiliki status "Dipanggil," "Dilayani," atau "Selesai Dilayani"
            const rows = antrianContainer.querySelectorAll("tbody tr");
            rows.forEach(row => {
                const statusCell = row.querySelector(".status-cell");
                const loketCell = row.querySelector(".loket-cell");
                const status = statusCell.textContent.trim();
                if (status === "Dipanggil" || status === "Dilayani" || status === "Selesai Dilayani") {
                    loketCell.style.display = "";
                } else {
                    loketCell.style.display = "none"; // Sembunyikan kolom "Loket" untuk status lainnya
                }
            });
        }

        // Fungsi untuk mengambil data antrian terbaru dari server dan memperbarui tampilan
        function fetchLatestQueue() {
            fetch("/pegawai/panggil")
                .then(response => response.text()) // Mengambil response sebagai HTML
                .then(data => {
                    // Mencari dan mengambil bagian yang berada di dalam elemen antrianContainer
                    const parser = new DOMParser();
                    const htmlDocument = parser.parseFromString(data, 'text/html');
                    const antrianContent = htmlDocument.getElementById("antrianContainer").innerHTML;

                    if (antrianContent) {
                        updateQueueView(antrianContent); // Perbarui tampilan antrian dengan konten yang diambil
                    } else {
                        console.error("Tidak dapat menemukan konten antrian.");
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        }

        // Jalankan fungsi fetchLatestQueue setiap beberapa detik
        setInterval(fetchLatestQueue, 5000); // Contoh: memperbarui setiap 5 detik
    </script>

    <script>
        function selectLoket() {
            const loketsSelect = document.getElementById("loketsSelect");
            selectedLoketId = loketsSelect.value; // Simpan loket yang dipilih
            const selectedLoketName = loketsSelect.options[loketsSelect.selectedIndex].text;
            const selectedLoketInfo = document.getElementById("selectedLoketInfo");
            const selectedLoketNameSpan = document.getElementById("selectedLoketName");

            if (selectedLoketId) {
                selectedLoketInfo.style.display = "block";
                selectedLoketNameSpan.textContent = selectedLoketName;
            } else {
                selectedLoketInfo.style.display = "none";
                selectedLoketNameSpan.textContent = "";
            }

            console.log(`Loket ${selectedLoketName} dipilih.`);
        }

        function callAntrian(nomorAntrian, id) {
            if (!selectedLoketId) {
                alert("Harap pilih loket terlebih dahulu.");
                return;
            }

            const speechConfig = {
                lang: 'id-ID',
                text: `Panggilan nomor antrian ${nomorAntrian} di loket ${selectedLoketId}`,
                rate: 0.9
            };

            const speechSynthesisUtterance = new SpeechSynthesisUtterance(speechConfig.text);
            speechSynthesisUtterance.lang = speechConfig.lang;
            speechSynthesisUtterance.rate = speechConfig.rate;

            speechSynthesis.speak(speechSynthesisUtterance);

            // Kirim permintaan ke server dengan nomor antrian, loket_id terpilih, dan status
            fetch(`/api/antrians/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        loket_id: selectedLoketId, // Menggunakan loketId yang dipilih sebelumnya
                        status: "Dipanggil" // Ganti status sesuai kebutuhan
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        console.log("Panggilan antrian berhasil diperbarui dengan ID loket.");
                        // Perbarui kolom "Loket" pada baris yang sesuai dengan nomor antrian yang dipanggil
                        const loketCell = document.querySelector(`[data-id="${id}"] .loket-cell`);
                        loketCell.textContent = selectedLoketId;
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        }

        function UbahDilayani(nomorAntrian, id) {
            if (!selectedLoketId) {
                alert("Harap pilih loket terlebih dahulu.");
                return;
            }

            // Kirim permintaan ke server dengan nomor antrian, loket_id terpilih, dan status
            fetch(`/api/antrians/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        loket_id: selectedLoketId, // Menggunakan loketId yang dipilih sebelumnya
                        status: "Dilayani" // Ganti status sesuai kebutuhan
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        console.log("Panggilan antrian berhasil diperbarui dengan ID loket.");
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        }

        function UbahSelesai(nomorAntrian, id) {
            if (!selectedLoketId) {
                alert("Harap pilih loket terlebih dahulu.");
                return;
            }

            // Kirim permintaan ke server dengan nomor antrian, loket_id terpilih, dan status
            fetch(`/api/antrians/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        loket_id: selectedLoketId, // Menggunakan loketId yang dipilih sebelumnya
                        status: "Selesai Dilayani" // Ganti status sesuai kebutuhan
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        console.log("Panggilan antrian berhasil diperbarui dengan ID loket.");
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        }

        function UbahTidakDatang(nomorAntrian, id) {
            if (!selectedLoketId) {
                alert("Harap pilih loket terlebih dahulu.");
                return;
            }

            // Kirim permintaan ke server dengan nomor antrian, loket_id terpilih, dan status
            fetch(`/api/antrians/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        loket_id: selectedLoketId, // Menggunakan loketId yang dipilih sebelumnya
                        status: "Tidak Datang" // Ganti status sesuai kebutuhan
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        console.log("Panggilan antrian berhasil diperbarui dengan ID loket.");
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        }
    </script>


    @endsection