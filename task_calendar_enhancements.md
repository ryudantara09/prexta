# Calendar Enhancements Plan

## Overview
The current calendar at `http://localhost/dashboard/interviews` provides a basic view of scheduled interviews. To improve utility and user experience, we can implement several enhancements focusing on **interactivity**, **filtering**, and **visual feedback**.

## 1. Advanced Interactivity

### A. Click-to-Schedule (`dateClick`)
**Goal:** Allow users to click on an empty day or time slot to immediately schedule an interview for that specific time.
- **Implementation:**
  - Add `interactionPlugin` (already present).
  - Implement the `dateClick` handler in `calendarOptions`.
  - When triggered, open the `CalendarSchedulingDialog` and pre-fill the `scheduled_at` field with the clicked date/time.
  - **Code Snippet:**
    ```javascript
    dateClick: function(info) {
        // Pre-fill form state or pass prop to dialog
        schedulingForm.scheduled_at = info.dateStr + 'T09:00'; // Default to 9am if day click, or specific time if time slot
        isDialogOpen.value = true;
    }
    ```

### B. Drag-and-Drop Rescheduling (`eventDrop`)
**Goal:** Allow users to reschedule interviews by simply dragging the event to a new time slot.
- **Implementation:**
  - Set `editable: true` in `calendarOptions`.
  - Implement `eventDrop` handler.
  - On drop, trigger an API call to update the interview's `scheduled_at` time.
  - Adding `eventResize` allows changing duration (if applicable).
  - **Code Snippet:**
    ```javascript
    eventDrop: function(info) {
        if (!confirm("Are you sure you want to reschedule this interview?")) {
            info.revert();
            return;
        }
        // Call Inertia form put/patch or axios request
        updateInterviewTime(info.event.id, info.event.start);
    }
    ```

## 2. Advanced Filtering Toolbar

**Goal:** Users might have many interviews. Allow them to filter the view.
- **Implementation:**
  - Create a filter toolbar above the `<FullCalendar />` component.
  - **Filters to Add:**
    1.  **Job Position:** Dropdown to show interviews only for a specific job.
    2.  **Status:** Checkboxes/Dropdown for `Pending`, `Scheduled`, `Completed`, `Cancelled`.
    3.  **Interviewer:** (If applicable) Filter by who is conducting the interview.
  - **Logic:**
    - Create a computed property `filteredEvents` that filters the raw `props.events` based on selected filter state.
    - Pass `filteredEvents` to FullCalendar instead of `props.events`.

## 3. Visual & UX Enhancements

### A. Custom Event Rendering (`eventContent`)
**Goal:** Make events look more informative at a glance.
- **Implementation:**
  - Use `eventContent` slot or function in FullCalendar.
  - **Display:**
    - Candidate Name (Bold).
    - Status Indicator (Color dot: Green for confirmed, Yellow for pending).
    - Small Avatar (if available).
  - **Markdown/Vue Integration:** FullCalendar Vue component supports slots like `#eventContent="{ event }"`.

### B. Business Hours & Now Indicator
**Goal:** Visual cues for availability and current time.
- **Implementation:**
  - Set `businessHours: { daysOfWeek: [1, 2, 3, 4, 5], startTime: '09:00', endTime: '17:00' }` to gray out non-work hours.
  - Set `nowIndicator: true` to show a red line for the current time in Week/Day views.

### C. Tooltips (Hover support)
**Goal:** Show details without clicking.
- **Implementation:**
  - Use `eventMouseEnter` and `eventMouseLeave`.
  - Integrate with a library like Tippy.js or use a simple absolute-positioned Vue component that follows the mouse or attaches to the element.
  - Show: Full Applicant Name, Job Title, Exact Time, Interviewer Notes.

## 4. Dark Mode Refinements

**Goal:** Ensure the calendar looks native in Dark Mode.
- **Current Issues:** FullCalendar's default CSS often conflicts with Tailwind dark mode or uses hardcoded light colors.
- **Tasks:**
  - Ensure all `--fc-*` variables in `<style>` block are correctly mapped to Tailwind's `gray-800`, `gray-900`, etc.
  - Verify modal dialogs opened from the calendar also follow dark mode (they likely do if using Shadcn/ui).

## Action Plan Checklist

- [ ] **Step 1:** Implement **Filtering Logic** (Computed property for events).
- [ ] **Step 2:** Add **Click-to-Schedule** (`dateClick`).
- [ ] **Step 3:** Enable **Drag-and-Drop** (`editable: true`, `eventDrop`).
- [ ] **Step 4:** Custom **Event Content** (Status colors, Applicant names).
- [ ] **Step 5:** Add **Business Hours** & **Now Indicator**.
