<?php

    namespace App\Models;

    class Configurator extends ProtoModel
    {
        public const TABLE_NAME = 'configurators';
        protected $table = self::TABLE_NAME;
        protected $casts = ['data' => 'array'];


        public function getLogo()
        {
            return $this->logo;
        }

        public function getDataItem(string $key)
        {
            return $this->data[$key] ?? '';
        }

        public function getEmail()
        {
            return $this->getDataItem('email');
        }

        public function getPhone()
        {
            return $this->getDataItem('phone');
        }

        public function getAddress()
        {
            return $this->getDataItem('address');
        }

        public function getSchedule()
        {
            return $this->getDataItem('schedule');
        }

        public function getCoordinates()
        {
            return $this->getDataItem('coordinates');
        }

        public function getRequisites()
        {
            return $this->getDataItem('requisites_text');
        }
    }
