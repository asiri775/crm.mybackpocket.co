<v-time-picker {{ $attributes }}>
    {{ $slot }}
</v-time-picker>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-time-picker-template"
    >
        <span class="relative inline-block w-full time-selector-box">
            <slot></slot>
            <i class="la la-clock pointer-events-none absolute top-1/2 -translate-y-1/2 text-2xl text-gray-400 ltr:right-2 rtl:left-2"></i>
            <select 
                class="time-dropdown-picker relative flex items-center justify-between rounded border border-gray-200 p-2 hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:text-gray-300"
                @change="updateFlatpickrValue"
            >
                <option value="00:00">00:00</option>
                <option value="00:30">00:30</option>
                <option value="01:00">01:00</option>
                <option value="01:30">01:30</option>
                <option value="02:00">02:00</option>
                <option value="02:30">02:30</option>
                <option value="03:00">03:00</option>
                <option value="03:30">03:30</option>
                <option value="04:00">04:00</option>
                <option value="04:30">04:30</option>
                <option value="05:00">05:00</option>
                <option value="05:30">05:30</option>
                <option value="06:00">06:00</option>
                <option value="06:30">06:30</option>
                <option value="07:00">07:00</option>
                <option value="07:30">07:30</option>
                <option value="08:00">08:00</option>
                <option value="08:30">08:30</option>
                <option value="09:00">09:00</option>
                <option value="09:30">09:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
                <option value="16:30">16:30</option>
                <option value="17:00">17:00</option>
                <option value="17:30">17:30</option>
                <option value="18:00">18:00</option>
                <option value="18:30">18:30</option>
                <option value="19:00">19:00</option>
                <option value="19:30">19:30</option>
                <option value="20:00">20:00</option>
                <option value="20:30">20:30</option>
                <option value="21:00">21:00</option>
                <option value="21:30">21:30</option>
                <option value="22:00">22:00</option>
                <option value="22:30">22:30</option>
                <option value="23:00">23:00</option>
                <option value="23:30">23:30</option>
              </select>
        </span>
    </script>

    <script type="module">
        app.component('v-time-picker', {
            template: '#v-time-picker-template',

            props: {
                name: String,
                value: String,
                allowInput: {
                    type: Boolean,
                    default: true,
                },
                disable: Array,
                minDate: String,
                maxDate: String,
            },

            data: function() {
                return {
                    datepicker: null
                };
            },

            mounted: function() {
                let options = this.setOptions();
                this.activate(options);
            },

            methods: {
                setOptions: function() {
                    let self = this;
                    return {
                        allowInput: this.allowInput ?? true,
                        disable: this.disable ?? [],
                        minDate: this.minDate ?? '',
                        maxDate: this.maxDate ?? '',
                        dateFormat: "H:i",
                        time_24hr: true,
                        enableTime: true,
                        noCalendar: true,
                        minuteIncrement: 30,
                        onChange: function(selectedDates, dateStr, instance) {
                            self.$emit("onChange", dateStr);
                        }
                    };
                },

                activate: function(options) {
                    let element = this.$el.getElementsByTagName("input")[0];

                    let initialVal = element.value;
                    let timeParts = initialVal.split(":");
                    initialVal = timeParts.length > 2 ? `${timeParts[0]}:${timeParts[1]}` : initialVal;

                    let timeElement = element.parentElement.querySelector("select");
                    timeElement.value = initialVal;

                    this.datepicker = new Flatpickr(element, options);
                },

                clear: function() {
                    this.datepicker.clear();
                },

                updateFlatpickrValue: function(event) {
                    let selectedTime = event.target.value;
                    this.datepicker.setDate(selectedTime, true);
                }
            }
        });
    </script>
@endPushOnce
