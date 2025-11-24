<x-filament-panels::page>
    @vite(['resources/js/app.js'])

    <style>
        #calendar {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 10px;
        }

        .fc-header-toolbar {
            margin-bottom: 1.5em;
        }

        .bg-white {
            background-color: #fff;
        }

        .p-4 {
            padding: 1rem;
        }

        .rounded-md {
            border-radius: 0.5rem;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="min-h-screen bg-gray-100">


        <!-- Create button -->




        <!-- Calendar -->
        <main class="bg-white p-4 rounded-md shadow-sm">
            {{ $this->btnCreateEvent() }}
            <div id="calendar"></div>
        </main>
    </div>

</x-filament-panels::page>
