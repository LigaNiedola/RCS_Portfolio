<?php
namespace Storage;
class CRUD {
  private $storage_path;
  private $expected_fields;

  private $template = [
    'last_id' => 0,
    'entries' => []
  ];

  public function __construct(string $storage_name, array $expected_fields) {
    $this->storage_path = PRIVATE_DIR . $storage_name . '.json';
    $this->expected_fields = $expected_fields;
  }

  public function getEntry ($id) {
    if (!isset($this->read()->entries[$id]))
      return false;

    return $this->read()->entries[$id];
  }

  public function getAllEntries () {
    return $this->read()->entries;
  }

  public function read() {
    if (!file_exists($this->storage_path)) {
      return (object) $this->template;
    }

    $content = file_get_contents($this->storage_path);
    return (object) json_decode($content, true);
  }

  public function getLastEntry() {
    if (count($this->read()->entries) === 0)
      return false;

    return end($this->read()->entries);
  }

  public function countEntries () {
    return count($this->read()->entries);
  }

  public function create($id, $new_entry) {
    $data = $this->read();
    // $id = ++$data->last_id;

    if (!isset($data->entries[$id])) {
      $data->entries[$id] = $this->filterData($new_entry);
    }

    file_put_contents($this->storage_path, json_encode($data, JSON_PRETTY_PRINT));

    return [
      "id" => $id,
      'entry' => $data->entries[$id]
    ];
  }

  public function deleteAll() {
    $data = (object) $this->template;
    file_put_contents($this->storage_path, json_encode($data, JSON_PRETTY_PRINT));
  }

  private function filterData($data) {
    return array_intersect_key($data, array_flip($this->expected_fields));
  }
}