<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

@php
    $maintenance_info = $maintenance_info = DB::table('maintenance_information')
        ->where('status', 7)
        ->where('type', 'admin_web')
        ->orderBy('created_at', 'DESC')
        ->first();
@endphp


@if ($maintenance_info)
    <div class="notif-info">
        <div style="display: flex;margin:auto;align-items: center;padding-bottom: 15px;" class="alertwfwfs">
            <div class="text-content-info">
                <i style="width:40px;height:40px;" class="fa fa-warning"></i>
                <h5 style="margin-bottom: 4px;margin-top: 4px;">{{ $maintenance_info->maintenance_information }}</h5>
                <p class="p-message">{{ $maintenance_info->message }}</p>

                <div class="date-info">
                    Tanggal:
                    {{ \Carbon\carbon::parse($maintenance_info->start_date)->format('d M Y') }}
                    {{ \Carbon\carbon::parse($maintenance_info->hour_start)->format('H:i') }}
                    &nbsp;
                    <span>s/d</span>
                    &nbsp;
                    {{ \Carbon\carbon::parse($maintenance_info->end_date)->format('d M Y') }}
                    {{ \Carbon\carbon::parse($maintenance_info->hour_end)->format('H:i') }}

                    <br>
                    <strong>Sisa Waktu:
                        <span id="countdown"></span>
                    </strong>

                </div>
            </div>
        </div>
    </div>
@endif





<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }

    .p-message {
        width: 500px;
    }

    h5 {
        font-size: 16px;
    }

    .notif-info {
        position: absolute;
        width: max-content;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        text-align: center;
        background: rgb(168, 0, 0);
        color: white;
        border-radius: 10px;
        padding: 10px;
        font-size: 13px;
    }

    @media (max-width: 70.5rem) {

        .notif-info {
            position: absolute;
            width: max-content;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            text-align: center;
            background: rgb(168, 0, 0);
            color: white;
            border-radius: 10px;
            padding: 10px;
            font-size: 13px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const endDate =
            "{{ $maintenance_info ? \Carbon\Carbon::parse($maintenance_info->end_date)->format('d M Y') : '' }}";
        const endTime =
            "{{ $maintenance_info ? \Carbon\Carbon::parse($maintenance_info->hour_end)->format('H:i:s') : '' }}";

        // Bentuk datetime lengkap
        const endDateTime = new Date(endDate + "T" + endTime);

        function updateCountdown() {

            const now = new Date();
            const distance = endDateTime.getTime() - now.getTime();

            if (distance <= 0) {
                document.getElementById("countdown").innerHTML = "Maintenance telah berakhir";
                clearInterval(interval);
                location.reload();
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML =
                `${days} Hari ${hours} Jam ${minutes} Menit ${seconds} Detik`;
        }

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);

    });
</script>
