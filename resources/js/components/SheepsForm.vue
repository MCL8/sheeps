<template>
    <div class="container">
        <div class="row pl-4">
            <h4>День {{ day }}</h4>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="corral1">Загон1</label>
                    <textarea class="form-control" id="corral1" name="corral1" rows="10" disabled>{{ cCorral1 }}</textarea>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="corral3">Загон3</label>
                    <textarea class="form-control" id="corral3" name="corral3" rows="10" disabled>{{ cCorral3 }}</textarea>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="corral2">Загон2</label>
                    <textarea class="form-control" id="corral2" name="corral2" rows="10" disabled>{{ cCorral2 }}</textarea>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="corral4">Загон4</label>
                    <textarea class="form-control" id="corral4" name="corral4" rows="10" disabled>{{ cCorral4 }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="d-flex justify-content-between">
                    <a href="/" class="btn btn-primary">Обновить</a>
                    <a href="/report" class="btn btn-primary">Отчет по дням</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import axios from 'axios';
    import r from '../route';

    export default {
        data() {
            return {
                sheeps : [],
                day: 1
            }
        },
        computed: {
            cCorral1 : function () {
                return this.getString(this.sheeps.corral1);
            },
            cCorral2 : function () {
                return this.getString(this.sheeps.corral2);
            },
            cCorral3 : function () {
                return this.getString(this.sheeps.corral3);
            },
            cCorral4 : function () {
                return this.getString(this.sheeps.corral4);
            }
        },
        methods: {
            getSheeps() {
                axios.get(r('sheeps.index'))
                    .then((response) => {
                        this.sheeps = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            setDay() {
              if (localStorage.day) {
                  this.day = localStorage.day;
              }
            },
            getString(arr) {
                let str = '';
                for (let index in arr) {
                    str += arr[index] + '\n';
                }
                return str;
            },
            dayTimer() {
                setInterval(this.nextDay, 10000);
            },
            nextDay() {
                this.makeReport(this.day);
                this.day++;
                localStorage.day = this.day;
                this.addSheep();
                if (this.day % 10 === 0) {
                    this.slaughterSheep();
                }
            },
            addSheep() {
                let keys = Object.keys(this.sheeps);

                for (let index in keys) {
                    if (this.sheeps[keys[index]].length > 1) {
                        let data = {corral: keys[index].replace(/\D+/g,""), day: this.day};

                        axios.post(r('sheeps.store'), data)
                            .then((response) => {
                                console.log(response);
                                this.getSheeps();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                }
            },
            slaughterSheep() {
                let keys = Object.keys(this.sheeps);
                let key = keys[this.randomIndex(keys.length)];
                let index = this.randomIndex(this.sheeps[key].length);
                let id = this.sheeps[key][index].replace(/\D+/g,"");

                axios.delete(r('sheeps.destroy') + id)
                    .then((response) => {
                        console.log(response);
                        this.sheeps[key].splice(index, 1)
                        if (this.sheeps[key].length <= 1) {
                            this.replaceSheep(key);
                        }
                        let data = {
                            day: this.day,
                            sheep_id: id,
                            corral: key.replace(/\D+/g,""),
                            event_name: 'deleted'
                        }
                        this.eventLog(data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            replaceSheep(corral) {
                let max = this.sheeps.corral1.length;
                let maxKey = 'corral1';

                for (let key in this.sheeps) {
                    if (max < this.sheeps[key].length) {
                        max = this.sheeps[key].length;
                        maxKey = key;
                    }
                }

                let id = this.sheeps[maxKey][max - 1].replace(/\D+/g,"");
                let data = {corral: corral.replace(/\D+/g,""), day: this.day};

                console.log(data);

                axios.put(r('sheeps.update') + id, data)
                    .then((response) => {
                        console.log(response);
                        let sheep = this.sheeps[maxKey].pop();
                        this.sheeps[corral].push(sheep);
                    })
                    .catch((error) => {
                        console.log(error);
                    });

            },
            randomIndex(max) {
                return Math.floor(Math.random() * max)
            },
            eventLog(data) {
                axios.post(r('events.store'), data)
                    .then((response) => {
                        console.log(response);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            makeReport(day) {
                axios.post(r('reports.store'), {day: day})
                    .then((response) => {
                        console.log(response);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }

        },
        mounted() {
            this.getSheeps();
            this.setDay();
            this.dayTimer();
        }
    }
</script>
