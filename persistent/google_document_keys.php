<?php


function getValue($entity, $type, $value) {

    $email = null;
    $name = null;
    $phone = null;
    $names = [];
    $namesAddresses = [];

    foreach ($entity->matter_contacts as $matterContact) {

        if ($matterContact->formatType() !== $type) {
            continue;
        }

        if (!is_null($matterContact->getName())) {
            array_push($names, $matterContact->getName());
        }

        if (!is_null($matterContact->getName())) {
            array_push($namesAddresses, $matterContact->getName() . ', ' . $matterContact->getAddress());
        }

        if (!is_null($matterContact->getEmail())) {
            $email = $matterContact->getEmail();
        }

        if (!is_null($matterContact->getName())) {
            $name = $matterContact->getName();
        }

        if (!is_null($matterContact->getPhone())) {
            $phone = $matterContact->getPhone();
        }

        if (!is_null($matterContact->getContactName())) {
            array_push($names, $matterContact->getContactName());
        }

        if (!is_null($matterContact->getContactName())) {
            array_push($namesAddresses, $matterContact->getContactName() . ', ' . $matterContact->getAddress());
        }

    }

    switch ($value) {

        case 'email' :

            return $email;

        case 'name' :

            return $name;

        case 'phone' :

            return $phone;

        case 'names' :

            return $names;

        case 'address' :

            return $namesAddresses;

        default :

            return [];

    }

}

return [
    'attorney_email' => function ($entity = null, $field = null) {

        return getValue($entity, 'Attorney(P)', 'email');

    },
    'attorney_phone' => function ($entity = null, $field = null) {

        return getValue($entity, 'Attorney(P)', 'phone');

    },
    'attorney_name' => function ($entity = null, $field = null) {

        return getValue($entity, 'Attorney(P)', 'name');

    },
    'plaintiffs_names' => function ($entity = null, $field = null) {

        return getValue($entity, 'Plaintiff', 'names');

    },
    'defendants_names' => function ($entity = null, $field = null) {

        return getValue($entity, 'Defendant', 'names');

    },
    'plaintiffs_with_address' => function ($entity = null, $field = null) {

        return getValue($entity, 'Plaintiff', 'address');

    },
    'defendants_with_address' => function ($entity = null, $field = null) {

        return getValue($entity, 'Defendant', 'address');

    }
];
