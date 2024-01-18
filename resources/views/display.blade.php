<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">



</head>


<body>
    <nav class="navbar bg-primary">
        <nav class="navbar navbar-expand-lg bg-primary text-uppercase fixed-top" style="height: 5rem;">
            <div class="container">
                <a class="navbar-brand text-white" href="#">
                    <a class="navbar-brand text-white">
                        <img id="logoImage" src="" style="max-width: 50px; max-height: 50px; margin-right: 25px">
                        <span id="itemName"></span>
                    </a>
                </a>
            </div>
        </nav>
    </nav>
    <br>
    <br>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetchPageData();
        });

        async function fetchPageData() {
            try {
                const response = await fetch("/api/pages"); // Update the URL accordingly
                const data = await response.json();

                if (data.status && data.page && data.page.length > 0) {
                    const logoImage = document.getElementById("logoImage");
                    const itemName = document.getElementById("itemName");
                    const pageDataContainer = document.getElementById("pageData");

                    // Update the logo image source and item name
                    logoImage.src = `{{ asset('storage/') }}/${data.page[0].logo}`;
                    itemName.textContent = data.page[0].nama;

                    let content = "";

                    data.page.forEach(page => {
                        content += `<div>${page.nama}</div>`; // Adjust how you want to display the data
                    });

                    pageDataContainer.innerHTML = content;
                } else {
                    console.error("Error fetching page data.");
                }
            } catch (error) {
                console.error("An error occurred:", error);
            }
        }
    </script>


    <div class="container">
        <div class="row" style="justify-content: center; margin: 40px;">
            <div class="card" style="width: 30rem; height: 17rem; text-align: center">
                <div class="card-body" id="loket-container">
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            const loketContainer = document.getElementById('loket-container');

            // Ganti URL_API_ANDA dengan URL API yang sesuai
            const apiUrl = 'http://localhost:8001/api/lokets';
            const apiUrlAntrian = 'http://localhost:8001/api/antrians';
            let lastQueueStatus = '';

            function updateQueueInfo() {
                $.get(apiUrl)
                    .done(data => {
                        if (data.status === true) {
                            const loket = data.data.find(loket => loket.id === 1);
                            if (loket) {
                                $('#loket-container').html(`
                        <br>
                        <h4>${loket.nama}</h4>
                        <br>
                        <h5>NOMOR ANTRIAN</h5>
                        <br>
                    `);

                                checkStatus(); // Start checking for status updates
                            } else {
                                $('#loket-container').html("Loket dengan ID 1 tidak ditemukan.");
                            }
                        } else {
                            $('#loket-container').html("Gagal mengambil data Loket dari API.");
                        }
                    })
                    .fail(error => {
                        console.error("Terjadi kesalahan:", error);
                        $('#loket-container').html("Terjadi kesalahan saat mengambil data dari API.");
                    });
            }

            let isNoQueueMessagePrinted = false; // Flag to track if the message is printed

            function checkStatus() {
                $.get(apiUrlAntrian)
                    .done(queueData => {
                        const queueForLoket = queueData.data.find(item => item.loket_id === 1 && item.status === 'Dipanggil');
                        if (queueForLoket) {
                            const queueNumber = queueForLoket.nomor_antrian;
                            if (queueNumber !== lastQueueStatus) {
                                lastQueueStatus = queueNumber;
                                $('#loket-container p').empty(); // Clear the container
                                $('#loket-container').append(`<h1>${queueNumber}</h1>`);
                            }
                        } else if (!isNoQueueMessagePrinted) {
                            isNoQueueMessagePrinted = true;
                            $('#loket-container h1').empty(); // Clear the container
                            $('#loket-container').append('<p>Belum ada antrian yang Dipanggil</p>');
                        }
                    })
                    .fail(error => {
                        console.error('Terjadi kesalahan:', error);
                    })
                    .always(() => {
                        setTimeout(checkStatus, 10000);
                    });
            }


            $(document).ready(function() {
                updateQueueInfo(); // Initial update
            });
        </script>

        <div class="row" id="table-body">
        </div>

        <style>
            #table-body {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
                margin: 20px;
            }


            .card {
                margin-bottom: 1rem;
                width: 15rem;
                height: 14rem;
                text-align: center;
                border: 1px solid #007BFF;
                border-radius: 10px;
                padding: 20px;
                background-color: #f8f9fa;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

            }
        </style>

        <script>
            const loketElement = document.getElementById('table-body');

            const apiUrl1 = 'http://localhost:8001/api/lokets';
            const apiUrlAntrian1 = 'http://localhost:8001/api/antrians';

            function fetchAndProcessLokets() {
                fetch(apiUrl1)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status && data.data) {
                            loketElement.innerHTML = '';

                            const loketsAboveID1 = data.data.filter(loket => loket.id > 1);

                            if (loketsAboveID1.length > 0) {
                                loketsAboveID1.forEach(loket => {
                                    const card = document.createElement('div');
                                    card.className = 'card';

                                    const cardBody = document.createElement('div');
                                    cardBody.className = 'card-body';

                                    const heading = document.createElement('h5');
                                    heading.textContent = loket.nama;

                                    const description = document.createElement('h6');
                                    description.textContent = 'NOMOR ANTRIAN';
                                    description.style.marginTop = '30px';
                                    description.style.marginBottom = '30px';

                                    cardBody.appendChild(heading);
                                    cardBody.appendChild(description);

                                    const queueContainer = document.createElement('div');
                                    queueContainer.className = 'queue-container'; // Class instead of ID
                                    cardBody.appendChild(queueContainer);

                                    card.appendChild(cardBody);
                                    loketElement.appendChild(card);

                                    checkStatus1(queueContainer, loket.id); // Call checkStatus1() for each loket
                                });
                            } else {
                                loketElement.innerHTML = 'Tidak ada loket dengan ID di atas 1';
                            }
                        } else {
                            loketElement.innerHTML = 'Data tidak ditemukan';
                        }
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan:', error);
                        loketElement.innerHTML = 'Terjadi kesalahan saat memuat data';
                    });
            }

            let isNoQueueMessagePrinted1 = false;
            const lastQueueStatus1 = '';

            function checkStatus1(queueContainer, loketId) {
                $.get(apiUrlAntrian1)
                    .done(queueData1 => {
                        const queueForLoket1 = queueData1.data.find(item => item.loket_id === loketId && item.status === 'Dipanggil');
                        if (queueForLoket1) {
                            const queueNumber1 = queueForLoket1.nomor_antrian;
                            if (queueNumber1 !== lastQueueStatus1[loketId]) {
                                lastQueueStatus1[loketId] = queueNumber1;
                                queueContainer.innerHTML = `<h1>${queueNumber1}</h1>`;
                            }
                            isNoQueueMessagePrinted1 = true;
                        } else if (!isNoQueueMessagePrinted1) {
                            queueContainer.innerHTML = '<p>Belum ada antrian yang Dipanggil</p>';
                        }
                    })
                    .fail(error => {
                        console.error('Terjadi kesalahan:', error);
                    })
                    .always(() => {
                        setTimeout(() => checkStatus1(queueContainer, loketId), 10000); // Repeat the status check after 1 second
                    });
            }

            fetchAndProcessLokets();
        </script>





    </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous">
</script>

</html>