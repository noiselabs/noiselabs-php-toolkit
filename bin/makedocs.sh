#!/bin/bash

SOURCE_PATH="./src"
TARGET_PATH="./doc/docblox"


install_docblox() {
	echo "Installing Docblox via PEAR..."
	pear channel-discover pear.docblox-project.org
	pear channel-discover pear.michelf.com
	pear install DocBlox/DocBlox
}

run_docblox() {
	echo "Running Docblox..."
	docblox run -d $SOURCE_PATH -t $TARGET_PATH
}

run_docblox
