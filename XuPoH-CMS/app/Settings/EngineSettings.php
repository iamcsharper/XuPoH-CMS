<?php

// TODO: реализовывать кастомные операции конфигурации.
// Вообще подобная херня необходима для адекватной работы с конфигурацией в админке
// Дабы не шаманить с категориями, всё проще сделать модульно и настраивать сугубо модули
class EngineSettings extends Settings {
	/**
	 * ИМЯ в ENGINE_ROOT/config/ИМЯ/config.json
	 */
	protected function getName() {
		return "engine";
	}
}