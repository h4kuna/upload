<?php

namespace h4kuna\Upload\Upload;

class FilterFactory
{

	public static function createImage()
	{
		return new ContentTypeFilter('image/png', 'image/pjpeg', 'image/jpeg', 'image/gif');
	}

	public static function createAudio()
	{
		return new ContentTypeFilter(
			'audio/mpeg3',
			'audio/x-mpeg-3',
			'audio/ogg',
			'audio/x-aiff');
	}

	public static function createDocument()
	{
		return new ContentTypeFilter(
			'text/plain',
			'application/msword',
			'application/vnd.ms-excel',
			'application/pdf',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.ms-powerpoint',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation');
	}

}