import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            themeSystem:'standard',
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            navLinks: true,
            editable: true,
            dayMaxEvents: true,
            events: '/api/events', // Your events endpoint
            dateClick: function(info) {
                alert('Clicked on: ' + info.dateStr);
                // You can add event creation logic here
            },
            eventClick: function(info) {
                alert('Event: ' + info.event.title);
                // You can add event editing logic here
            }
        });
        
        calendar.render();
    }
});