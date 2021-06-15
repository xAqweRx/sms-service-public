<?php


namespace App\Models\Permission;

/**
 * Class PermissionAdditional
 *
 * @package App\Models\Permission
 * @property array $types - for identification if user has permissions to see subset of resource.
 * @property array $accessList
 * @property array $accessOne
 * @property array $accessWrite
 * @property array $actions
 * @property array $forbiddenActions
 * @property array $restrictions
 * @property array $restrictionArray
 *
 * {
 *        // possible duplicates of fields
 *        // - but necessary to identify correct sub-resource
         'types' : [ { 'field' => 'value' }, ]
         'restrictions' : {
              'filters': <filters>,
              'filterDefinitions'?: <definitions>
         },
         'access' : {
              'list': <fields>,
              'one': <fields>,
              'write': <fields>,
          }
          'actions' : string[],
          'forbiddenActions': string[]
      }
 */
class PermissionItemAdditional
{
    /** @var array */
    private $dataSet;

    const FIELD_ACCESS = 'access';
    const FIELD_RESTRICTIONS = 'restrictions';
    const FIELD_RESTRICTIONS_ARRAY = 'restrictions';
    const FIELD_TYPES = 'types';
    const FIELD_ACTIONS = 'actions';
    const FIELD_FORBIDDEN_ACTIONS = 'forbiddenActions';

    const ACCESS_FIELDS = [
        'accessList', 'accessOne', 'accessWrite'
    ];

    const ACTIONS_AND_RESTRICTIONS = [
        self::FIELD_ACTIONS, self::FIELD_FORBIDDEN_ACTIONS, self::FIELD_RESTRICTIONS, self::FIELD_RESTRICTIONS_ARRAY
    ];



    /**
     * PermissionItemAdditional constructor.
     * If just an array is provided - assuming this is a type.
     *
     * @param array $additional
     */
    public function __construct( array $additional ) {
        $this->dataSet = $additional;
        if ( !$this->isValid() ) {
            $type = $this->dataSet;
            $this->dataSet = [];
            $this->dataSet[ 'types' ][]= $type;
        }
    }

    function isValid() {
        return
            isset($this->dataSet[self::FIELD_TYPES]) ||
            isset($this->dataSet[self::FIELD_ACCESS]) ||
            isset($this->dataSet[self::FIELD_ACTIONS]) ||
            isset($this->dataSet[self::FIELD_FORBIDDEN_ACTIONS]) ||
            isset($this->dataSet[self::FIELD_RESTRICTIONS]);
    }

    /**
     * @param $name
     * @return false|array
     */
    function __get($name) {
        if ( in_array($name, self::ACCESS_FIELDS) ) {
            $field = strtolower(str_replace(self::FIELD_ACCESS, '', $name));

            return $this->dataSet[ self::FIELD_ACCESS ][ $field ] ?? [];
        }
        if ( in_array($name, self::ACTIONS_AND_RESTRICTIONS) ) {
            return $this->dataSet[ $name ] ?? [];
        }
        if ( $name == self::FIELD_RESTRICTIONS ) {
            return $this->dataSet[ self::FIELD_RESTRICTIONS ] ?? [];
        }
        if ( $name == self::FIELD_TYPES ) {
            return $this->dataSet[ self::FIELD_TYPES ] ?? [];
        }
        if ( $name == self::FIELD_ACTIONS ) {
            return $this->dataSet[ self::FIELD_ACTIONS ] ?? [];
        }
        if ( $name == self::FIELD_FORBIDDEN_ACTIONS ) {
            return $this->dataSet[ self::FIELD_FORBIDDEN_ACTIONS ] ?? [];
        }
        return false;
    }

    function __isset( $name ) {
        return array_key_exists($name, $this->dataSet);
    }

    /**
     * @param PermissionItemAdditional $mergeFrom
     */
    function merge(PermissionItemAdditional $mergeFrom) {
        foreach ( self::ACCESS_FIELDS as $f ) {
            $field = strtolower(str_replace(self::FIELD_ACCESS, '', $f));
            $this->dataSet[ self::FIELD_ACCESS ][ $field ] = array_unique(array_merge($this->$f, $mergeFrom->$f));
        }

        if ( empty($this->actions) || empty($mergeFrom->actions) ) {
            $this->dataSet[ self::FIELD_ACTIONS ] = null;
        } else {
            $this->dataSet[ self::FIELD_ACTIONS ] = array_unique(array_merge($this->actions, $mergeFrom->actions));
        }

        if ( empty($this->forbiddenActions) || empty($mergeFrom->forbiddenActions) ) {
            $this->dataSet[ self::FIELD_FORBIDDEN_ACTIONS ] = null;
        } else {
            $this->dataSet[ self::FIELD_FORBIDDEN_ACTIONS ] = array_unique(
                array_merge($this->forbiddenActions, $mergeFrom->forbiddenActions)
            );
        }

        // case if current restrictions empty - override
        if (empty($this->restrictions)) {
            $this->dataSet['restrictions'] = $mergeFrom->restrictions;
        }
        // case if current and merged restrictions is not empty
        elseIf (!empty($mergeFrom->restrictions)) {
            if (!$this->dataSet['restrictionArray']) {
                $this->dataSet['restrictionArray'] = [$this->restrictions];
            }

            $this->dataSet['restrictionArray'][] = $mergeFrom->restrictions;
        }

        // if empty one of types permissions - supposing that user have access to all
        // only if all types are filled - checks according all opened types will be provided
        if ( empty($this->types) || empty($mergeFrom->types) ) {
            $this->dataSet[ 'types' ] = null;
        } else {
            $this->dataSet[ 'types' ] = array_unique(array_merge($this->dataSet[ 'types' ], $mergeFrom->types), SORT_REGULAR);
        }
    }
    /**
     * @return array
     */
    public function getDataSet(): array {
        return $this->dataSet;
    }
}
