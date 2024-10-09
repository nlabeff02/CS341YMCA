document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.getElementById('calendar');
    const monthYear = document.getElementById('month-year');
    const daysContainer = document.getElementById('days');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const addEventForm = document.getElementById('add-event-form');
    const eventNameInput = document.getElementById('event-name');
    const eventDateInput = document.getElementById('event-date');
    const eventList = document.getElementById('events');
    const selectedDateElement = document.getElementById('selected-date');

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let events = {};

    function renderCalendar() {
        daysContainer.innerHTML = '';
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        monthYear.textContent = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear}`;

        for (let i = 0; i < firstDay; i++) {
            daysContainer.innerHTML += '<div></div>';
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.textContent = i;
            dayElement.addEventListener('click', () => showEvents(i));
            daysContainer.appendChild(dayElement);
        }
    }

    function showEvents(day) {
        const dateKey = `${currentYear}-${currentMonth + 1}-${day}`;
        selectedDateElement.textContent = new Date(currentYear, currentMonth, day).toDateString();
        eventList.innerHTML = '';

        if (events[dateKey]) {
            events[dateKey].forEach(event => {
                const eventItem = document.createElement('li');
                eventItem.textContent = event;
                eventList.appendChild(eventItem);
            });
        } else {
            eventList.innerHTML = '<li>No events</li>';
        }
    }

    prevButton.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    nextButton.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });

    if (addEventForm) {
        addEventForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const eventName = eventNameInput.value;
            const eventDate = new Date(eventDateInput.value);
            const dateKey = `${eventDate.getFullYear()}-${eventDate.getMonth() + 1}-${eventDate.getDate()}`;

            if (!events[dateKey]) {
                events[dateKey] = [];
            }
            events[dateKey].push(eventName);

            alert(`Event "${eventName}" added on ${eventDate.toDateString()}`);
            showEvents(eventDate.getDate());
        });
    }

    renderCalendar();
});
