<?php

// This library class identifies and finds password ids

class Labels_Middleware {

	// updates on books

	function returnLabels(){
		$labels = new labels;
		$alllabels = $labels->returnLabels();
		if ($alllabels){
			Output::Success($alllabels);
		}
		Output::Error("There was an error");
	}

	function returnLoginLabels($loginid){
		$labels = new labels;
		$alllabels = $labels->returnLoginLabels($loginid);
		if ($alllabels){
			Output::Success($alllabels);
		}
		Output::Error("There was an error");
	}

	function addLabel(){
		$labels = new labels;
		if ($labels->addLabel($_POST['loginid'],$_POST['label'])){
			Output::Success("Your label was added successfully");
		}
		Output::Error("There was an error adding your label");
		// Return all my groups
	}

	function removeLabel(){
		$labels = new labels;
		if ($labels->removeLabel($_POST['loginid'],$_POST['label'])){
			Output::Success("Your label was removed successfully");
		}
		Output::Error("There was an error removing your label");
		// Return all my groups
	}

	function removeLabelFromEvery(){
		$labels = new labels;
		if ($labels->removeLabelfromEvery($_POST['label'])){
			Output::Success("Your label was removed successfully");
		}
		Output::Error("There was an error removing your label");
	}

	function searchByLabel($label){
		$password = new Password;
		$alllabels = $password->returnLoginsbyLabel($label);
		if ($alllabels){
			Output::Success($alllabels);
		}
		Output::Error("There was an error fetching your logins");
	}

	
}