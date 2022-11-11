//Import React
import React from "react";
//Import test renderer
import { render, fireEvent, cleanup } from "@testing-library/react";
//Import component to test
import { Editor } from "./Edit";

describe("Editor componet", () => {
	afterEach(cleanup);
	it("matches snapshot when selected", () => {
		const onChange = jest.fn();
		const { container } = render(
			<Editor onChange={onChange} value={"Tacos"} isSelected="true" />
		);
		expect(container).toMatchSnapshot();
	});

	it("matches snapshot when not selected", () => {
		const onChange = jest.fn();
		const { container } = render(
			<Editor onChange={onChange} value={"Tacos"} isSelected="false" />
		);
		expect(container).toMatchSnapshot();
	});

	it("Calls the onchange function", () => {
		const onChange = jest.fn();
		const { getByDisplayValue } = render(
			<Editor onChange={onChange} value={"Salad"} isSelected="false" />
		);
		fireEvent.change(getByDisplayValue("Salad"), {
			target: { value: "New Value" }
		});
		expect(onChange).toHaveBeenCalledTimes(1);
	});

	it("Passes updated value, not event to onChange callback", () => {
		const onChange = jest.fn();
		const { getByDisplayValue } = render(
			<Editor onChange={onChange} value={"Seltzer"} isSelected="false" />
		);
		fireEvent.change(getByDisplayValue("Seltzer"), {
			target: { value: "Boring Water" }
		});
		expect(onChange).toHaveBeenCalledWith("Boring Water");
	});
});
