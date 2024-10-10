document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.getElementById('calendar');
    const monthYear = document.getElementById('month-year');
    const daysContainer = document.getElementById('days');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const eventList = document.getElementById('events');
    const selectedDateElement = document.getElementById('selected-date');

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

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
        eventList.innerHTML = '<li>Loading classes...</li>';  // Show loading text
    
        //fetch classes for the selected date
        fetch(`php/get_classes.php?date=${currentYear}-${currentMonth + 1}-${day}`)
            .then(response => response.json())
            .then(data => {
                eventList.innerHTML = ''; // Clear the loading text
                if (data.length > 0) {
                    data.forEach(classItem => {
                        const eventItem = document.createElement('li');
                        eventItem.innerHTML = `${classItem.ClassName} (${classItem.StartTime} - ${classItem.EndTime}) at ${classItem.Location} <button onclick="registerClass(${classItem.ClassID})">Register</button>`;
                        eventList.appendChild(eventItem);
                    });
                } else {
                    eventList.innerHTML = '<li>No classes on this date</li>';
                }
            })
            .catch(error => {
                eventList.innerHTML = '<li>Error fetching classes</li>';
                console.error('Error:', error);
            });
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

    renderCalendar();
});
